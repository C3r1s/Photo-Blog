namespace Photo_Blog.DTOs;

public class PostCreateRequest
{
    public string Description { get; set; } = string.Empty;
    public string ImageUrl { get; set; } = string.Empty;
    public int UserId { get; set; }
}