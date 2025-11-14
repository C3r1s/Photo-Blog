using System.Text.Json;
using Microsoft.AspNetCore.Mvc;
using Photo_Blog.DTOs;
using Photo_Blog.Server.BL;

var builder = WebApplication.CreateBuilder(args);
var app = builder.Build();
app.UseHttpsRedirection();

var service = new ServerService();

app.MapGet("/posts", async () => await service.GetPosts());
app.MapGet("/users/{username}", async (string username) =>
{
    var user = await service.GetUserByUsername(username);
    if (user == null)
        return Results.NotFound("User not found");
    return Results.Ok(user);
});
app.MapGet("/users/{username}/posts", async (string username) =>
{
    var posts = await service.GetPostsByUser(username);
    return Results.Ok(posts);
});
app.MapPost("/posts", async (PostCreateRequest request) => 
{
    var post = new Post
    {
        UserId = request.UserId,
        Description = request.Description,
        ImageUrl = request.ImageUrl,
        Likes = 0,
        CreatedAt = DateTime.UtcNow
    };
    await service.AddPost(request.UserId, post);
    return Results.Created($"/posts/{post.Id}", post);
});

app.MapPost("/posts/get", async ([FromBody] PostGetRequest request) =>
{
    var post = await service.GetPostById(request.Id);
    if (post == null) return Results.NotFound();
    return Results.Ok(post);
});
app.MapPost("/posts/update", async ([FromBody] PostUpdateRequest request) =>
{
    await service.UpdatePost(request.UserId, request.Role, new Post
    {
        Id = request.Id,
        Description = request.Description,
        ImageUrl = request.ImageUrl
    });
    return Results.Ok();
});
app.MapPost("/posts/delete", async ([FromBody] DeletePostRequest request) =>
{
    await service.DeletePost(request.Id, request.UserId, request.Role);
    return Results.Ok();
});


app.MapPost("/users", async (User user) => await service.CreateUser(user));
app.MapPost("/users/login", ([FromBody] LoginRequest request) => service.GetUser(request.Email, request.Password));
app.MapPut("/posts/like", async (Post post) => await service.LikePost(post.Id));
app.MapPut("/users/profile", async (User updateData) =>
{
    await service.UpdateUserProfile(updateData.Id, updateData.Username, updateData.Avatar);
    return Results.Ok();
});

app.MapPut("/users/password", async ([FromBody] ChangePasswordRequest request) =>
{
    await service.ChangePassword(request.Email, request.OldPassword, request.NewPassword);
    return Results.Ok();
});

app.MapPost("/posts/like", async (LikePostRequest request) => 
{
    var result = await service.ToggleLikeAsync(request.PostId, request.UserId);
    return Results.Ok(new { liked = result });
});

app.MapPost("/posts/admin-likes", async (HttpContext context) =>
{
    using var reader = new StreamReader(context.Request.Body);
    var body = await reader.ReadToEndAsync();
    var data = JsonSerializer.Deserialize<JsonElement>(body);

    int postId = data.GetProperty("postId").GetInt32();
    int likes = data.GetProperty("likes").GetInt32();

    var newLikes = await service.SetAdminLikesAsync(postId, likes);
    return Results.Ok(new { likes = newLikes });
});

app.MapGet("/users/{userId}/likes", async (int userId) => Results.Ok(await service.GetUserLikesAsync(userId)));

app.Run();