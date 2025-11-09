using Microsoft.EntityFrameworkCore;
using Photo_Blog.DTOs;

namespace Photo_Blog.Server.DAL;

public class DatabaseContext(string connectionString) : DbContext
{
    public DbSet<Post> Posts { get; set; }
    public DbSet<User> Users { get; set; }

    protected override void OnConfiguring(DbContextOptionsBuilder optionsBuilder)
    {
        optionsBuilder.UseNpgsql();
    }

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        modelBuilder.Entity<Post>()
            .HasOne(p => p.Author)
            .WithMany()
            .HasForeignKey(p => p.UserId);
        modelBuilder.Entity<User>().Property(u => u.Username).HasMaxLength(50);
    }
}