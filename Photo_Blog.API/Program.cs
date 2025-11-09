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
app.MapPost("/posts", async (Post post) => await service.AddPost(post));
app.MapPut("/posts", async (Post post, int id, string role) => await service.UpdatePost(post, id, role));
app.MapPost("/users", async (User user) => await service.CreateUser(user));
app.MapPost("/users/login", ([FromBody]LoginRequest request) => service.GetUser(request.Email, request.Password));
app.MapPut("/posts/like", async (Post post) => await service.LikePost(post.Id));
app.MapPut("/users/profile", async (UpdateProfileRequest request) =>
{
    try
    {
        await service.UpdateUserProfile(request.CurrentUsername, request.Avatar);
        return Results.Ok();
    }
    catch (InvalidOperationException ex)
    {
        return Results.NotFound(ex.Message);
    }
});
app.Run();