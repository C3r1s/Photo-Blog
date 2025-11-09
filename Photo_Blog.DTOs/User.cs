using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace Photo_Blog.DTOs;

public class User
{
    [Key]
    [DatabaseGenerated(DatabaseGeneratedOption.Identity)]
    public int Id { get; set; }

    [Required] [MaxLength(100)] public string Username { get; set; }

    [Required] [MaxLength(20)] public string Password { get; set; }

    [Required] [MaxLength(30)] public string Email { get; set; }

    [Required] public string? Avatar { get; set; }

    [Required] public int Followers { get; set; }

    [Required] public string Role { get; set; } = "user";
}