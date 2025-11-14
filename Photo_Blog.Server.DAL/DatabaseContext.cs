using Microsoft.EntityFrameworkCore;
using Photo_Blog.DTOs;

namespace Photo_Blog.Server.DAL;

public class DatabaseContext(string connectionString) : DbContext
{
    public DbSet<Post> Posts { get; set; }
    public DbSet<User> Users { get; set; }
    public DbSet<Like> Likes { get; set; }

    protected override void OnConfiguring(DbContextOptionsBuilder optionsBuilder)
    {
        optionsBuilder.UseNpgsql(connectionString);
    }

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        modelBuilder.Entity<Post>()
            .HasOne(p => p.Author)
            .WithMany()
            .HasForeignKey(p => p.UserId);

        modelBuilder.Entity<User>()
            .Property(u => u.Username)
            .HasMaxLength(50);

        modelBuilder.Entity<Like>()
            .HasOne(l => l.Post)
            .WithMany(p => p.LikesCollection)
            .HasForeignKey(l => l.PostId)
            .OnDelete(DeleteBehavior.Cascade); 

        modelBuilder.Entity<Like>()
            .HasIndex(l => new { l.PostId, l.UserId })
            .IsUnique();
    }
}