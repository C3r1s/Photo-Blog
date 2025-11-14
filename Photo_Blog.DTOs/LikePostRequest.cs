namespace Photo_Blog.DTOs;

public class LikePostRequest
{
    public int PostId { get; set; }
    public int UserId { get; set; }
    public string Role { get; set; } = "user";
}