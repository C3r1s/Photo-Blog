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
app.MapPost("/posts", async (Post post) => await service.AddPost(post));
app.MapPut("/posts", async (Post post, int id, string role) => await service.UpdatePost(post, id, role));
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

app.Run();