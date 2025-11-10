using Microsoft.EntityFrameworkCore.Design;
using Microsoft.Extensions.Configuration;

namespace Photo_Blog.Server.DAL;

public class DbContextFactory : IDesignTimeDbContextFactory<DatabaseContext>
{
    public DatabaseContext CreateDbContext(string[] args)
    {
        var config = new ConfigurationBuilder()
            .SetBasePath(Directory.GetCurrentDirectory())
            .AddJsonFile("appsettings.json")
            .Build();

#if DEBUG
        var connectionString = config.GetConnectionString("TestConnection");
        Console.WriteLine($"Using connection string: {connectionString}");
#elif RELEASE
        var connectionString = config.GetConnectionString("PublicConnection");
            Console.WriteLine($"Using connection string: {connectionString}");
#endif
        return new DatabaseContext(connectionString);
    }

    private string[] _args => [];
    public DatabaseContext CreateDbContext() => CreateDbContext(_args);
}