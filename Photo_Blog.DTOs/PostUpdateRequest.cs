namespace Photo_Blog.DTOs;

public class PostUpdateRequest
{
    public int Id { get; set; }
    public string Description { get; set; } = string.Empty;
    public string ImageUrl { get; set; } = string.Empty;
    public int UserId { get; set; }
    public string Role { get; set; } = "user";
}