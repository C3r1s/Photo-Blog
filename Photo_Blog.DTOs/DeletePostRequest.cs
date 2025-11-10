namespace Photo_Blog.DTOs;

public class DeletePostRequest
{
    public int Id { get; set; }
    public int UserId { get; set; }
    public string Role { get; set; } = "user";
}