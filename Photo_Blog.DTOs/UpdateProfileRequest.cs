namespace Photo_Blog.DTOs;

public class UpdateProfileRequest
{
    public string CurrentUsername { get; set; } = string.Empty;
    public string? Avatar { get; set; }
}