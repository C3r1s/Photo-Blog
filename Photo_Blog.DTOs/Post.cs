using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace Photo_Blog.DTOs;

public class Post
{
    [Key]
    [DatabaseGenerated(DatabaseGeneratedOption.Identity)]
    public int Id { get; set; }

    [Required]
    public int UserId { get; set; }

    [ForeignKey("UserId")]
    public User? Author { get; set; }

    [Required]
    [MaxLength(500)]
    public string Description { get; set; }

    public int Likes { get; set; }

    public DateTime CreatedAt { get; set; } = DateTime.UtcNow;

    public string ImageUrl { get; set; }
    
    public void Like()
    {
        Likes++;
    }
}