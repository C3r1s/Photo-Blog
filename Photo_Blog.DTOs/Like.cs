using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace Photo_Blog.DTOs;

public class Like
{
    [Key]
    [DatabaseGenerated(DatabaseGeneratedOption.Identity)]
    public int Id { get; set; }

    [Required]
    public int PostId { get; set; }

    [Required]
    public int UserId { get; set; }

    [ForeignKey("PostId")]
    public virtual Post? Post { get; set; }

    public DateTime CreatedAt { get; set; } = DateTime.UtcNow;
}