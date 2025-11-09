using Microsoft.EntityFrameworkCore;
using Photo_Blog.DTOs;
using Photo_Blog.Server.DAL;

namespace Photo_Blog.Server.BL;

public class ServerService
{
    private readonly DbContextFactory _dbContextFactory = new DbContextFactory();


    public async Task<IEnumerable<Post>> GetPosts()
    {
        var context = _dbContextFactory.CreateDbContext();
        return await context.Posts.OrderByDescending(p => p.CreatedAt).ToListAsync();
    }

    public async Task AddPost(Post post)
    {
        var context = _dbContextFactory.CreateDbContext();
        var postToLoad = new Post
        {
            Author = post.Author,
            Description = post.Description,
            ImageUrl = post.ImageUrl ?? string.Empty,
            Likes = post.Likes,
            CreatedAt = post.CreatedAt
        };
        await context.Posts.AddAsync(postToLoad);
        await context.SaveChangesAsync();
    }

    public async Task UpdatePost(Post updatedPost, int requestingUserId, string requestingUserRole)
    {
        var context = _dbContextFactory.CreateDbContext();

        var existingPost = await context.Posts.FindAsync(updatedPost.Id);

        if (existingPost == null)
            throw new InvalidOperationException("Post not found");

        // Проверка прав
        if (requestingUserRole != "admin" && existingPost.UserId != requestingUserId)
            throw new UnauthorizedAccessException("You can only edit your own posts.");

        // Обновляем поля
        existingPost.Description = updatedPost.Description;
        existingPost.ImageUrl = updatedPost.ImageUrl;

        context.Entry(existingPost).State = EntityState.Modified;

        await context.SaveChangesAsync();
    }

    public async Task DeletePost(Post post, int requestingUserId, string requestingUserRole)
    {
        var context = _dbContextFactory.CreateDbContext();
        var existingPost = await context.Posts.FindAsync(post.Id);
        if (existingPost == null)
            throw new InvalidOperationException("Post not found");

        // Проверка прав
        if (requestingUserRole != "admin" && existingPost.UserId != requestingUserId)
            throw new UnauthorizedAccessException("You can only edit your own posts.");
        context.Posts.Remove(post);
        await context.SaveChangesAsync();
    }

    public async Task CreateUser(User user)
    {
        var context = _dbContextFactory.CreateDbContext();
        var hashedPassword = BCrypt.Net.BCrypt.HashPassword(user.Password);
        var userToLoad = new User()
        {
            Username = user.Username,
            Email = user.Email,
            Password = hashedPassword,
            Avatar = user.Avatar ?? string.Empty,
            Followers = 0
        };
        context.Users.Add(userToLoad);
        await context.SaveChangesAsync();
    }

    public async Task<User> GetUser(string email, string password)
    {
        var context = _dbContextFactory.CreateDbContext();
    
        // 1. Находим пользователя по email
        var user = await context.Users.FirstOrDefaultAsync(u => u.Email == email);
    
        if (user == null)
            throw new UnauthorizedAccessException("Invalid email or password");

        // 2. Проверяем пароль через BCrypt
        if (!BCrypt.Net.BCrypt.Verify(password, user.Password))
            throw new UnauthorizedAccessException("Invalid email or password");

        // 3. Возвращаем пользователя БЕЗ пароля (безопасность!)
        return new User
        {
            Id = user.Id,
            Username = user.Username,
            Email = user.Email,
            Avatar = user.Avatar,
            Followers = user.Followers,
            Role = user.Role
        };
    }
    
    public async Task<User?> GetUserByUsername(string username)
    {
        var context = _dbContextFactory.CreateDbContext();
        return await context.Users.FirstOrDefaultAsync(u => u.Username == username);
    }

    public async Task LikePost(int postId)
    {
        var context = _dbContextFactory.CreateDbContext();
        var post = await context.Posts.FindAsync(postId);
        if (post != null)
        {
            post.Likes++;
            await context.SaveChangesAsync();
        }
        else
        {
            throw new InvalidOperationException("Post not found");
        }
    }
    
    public async Task UpdateUserProfile(string currentUsername, string? newAvatar)
    {
        var context = _dbContextFactory.CreateDbContext();
        var user = await context.Users.FirstOrDefaultAsync(u => u.Username == currentUsername);
        if (user == null)
            throw new InvalidOperationException("User not found");

        user.Avatar = newAvatar ?? user.Avatar; // обновляем только если передано
        context.Entry(user).State = EntityState.Modified;
        await context.SaveChangesAsync();
    }        
}