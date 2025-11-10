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
        return await context.Posts
            .Include(p => p.Author) // ← подгружаем связанный User
            .OrderByDescending(p => p.CreatedAt)
            .ToListAsync();
    }
    
    public async Task<Post?> GetPostById(int postId)
    {
        var context = _dbContextFactory.CreateDbContext();
        return await context.Posts
            .Include(p => p.Author)
            .FirstOrDefaultAsync(p => p.Id == postId);
    }

    public async Task AddPost(int userId, Post post)
    {
        var context = _dbContextFactory.CreateDbContext();
        var newPost = new Post
        {
            UserId = userId,
            Description = post.Description,
            ImageUrl = post.ImageUrl ?? string.Empty,
            Likes = 0,
            CreatedAt = DateTime.UtcNow,
            Author = context.Users.Find(userId)
        };
        await context.Posts.AddAsync(newPost);
        await context.SaveChangesAsync();
    }

    public async Task UpdatePost(int requestingUserId, string requestingUserRole, Post updatedPost)
    {
        var context = _dbContextFactory.CreateDbContext();
        var post = await context.Posts.FindAsync(updatedPost.Id);
        if (post == null) throw new InvalidOperationException("Post not found");

        if (requestingUserRole != "admin" && post.UserId != requestingUserId)
            throw new UnauthorizedAccessException("You can only edit your own posts.");

        post.Description = updatedPost.Description;
        post.ImageUrl = updatedPost.ImageUrl ?? post.ImageUrl;
        context.Entry(post).State = EntityState.Modified;
        await context.SaveChangesAsync();
    }

    public async Task DeletePost(int postId, int requestingUserId, string requestingUserRole)
    {
        var context = _dbContextFactory.CreateDbContext();
        var post = await context.Posts.FindAsync(postId);
        if (post == null) throw new InvalidOperationException("Post not found");

        if (requestingUserRole != "admin" && post.UserId != requestingUserId)
            throw new UnauthorizedAccessException("You can only delete your own posts.");

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

        var user = await context.Users.FirstOrDefaultAsync(u => u.Email == email);

        if (user == null)
            throw new UnauthorizedAccessException("Invalid email or password");

        if (!BCrypt.Net.BCrypt.Verify(password, user.Password))
            throw new UnauthorizedAccessException("Invalid email or password");

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

    public async Task UpdateUserProfile(int userId, string newUsername, string? newAvatar)
    {
        var context = _dbContextFactory.CreateDbContext();
        var user = await context.Users.FindAsync(userId);
        if (user == null) throw new InvalidOperationException("User not found");

        user.Username = newUsername;
        user.Avatar = newAvatar ?? user.Avatar;

        context.Entry(user).State = EntityState.Modified;
        await context.SaveChangesAsync();
    }

    public async Task ChangePassword(string email, string oldPassword, string newPassword)
    {
        var context = _dbContextFactory.CreateDbContext();
        var user = await context.Users.FirstOrDefaultAsync(u => u.Email == email);
        if (user == null || !BCrypt.Net.BCrypt.Verify(oldPassword, user.Password))
            throw new UnauthorizedAccessException("Invalid credentials");

        user.Password = BCrypt.Net.BCrypt.HashPassword(newPassword);
        context.Entry(user).State = EntityState.Modified;
        await context.SaveChangesAsync();
    }

    public async Task<IEnumerable<Post>> GetPostsByUser(string username)
    {
        var context = _dbContextFactory.CreateDbContext();
        return await context.Posts
            .Where(p => p.Author.Username == username)
            .OrderByDescending(p => p.CreatedAt)
            .ToListAsync<Post>();
    }
}