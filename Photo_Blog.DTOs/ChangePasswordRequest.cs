namespace Photo_Blog.DTOs;

public class ChangePasswordRequest
{
    public string Email { get; set; } = string.Empty;
    public string OldPassword { get; set; } = string.Empty;
    public string NewPassword { get; set; } = string.Empty;
}