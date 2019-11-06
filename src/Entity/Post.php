<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $featured_image;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $file;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="post")
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getFeaturedImage(): ?string
    {
        return $this->featured_image;
    }

    public function setFeaturedImage(?string $featured_image): self
    {
        $this->featured_image = $featured_image;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('title', new Assert\Length([
            'min' => 2,
            'max' => 250,
            'minMessage' => 'The title must be at least {{ limit }} characters long',
            'maxMessage' => 'The title cannot be longer than {{ limit }} characters',
        ]));
    }

    public function removeUploadedImages($path, $thumbPath)
    {
        $path =  $path.'/'.$this->getFeaturedImage();
        $thumbPath =  $thumbPath.'/'.$this->getFeaturedImage();
        //remove old file if exists
        if (file_exists( $path ) && $this->getFeaturedImage() != NULL)
            unlink ( $path );

        if (file_exists( $thumbPath ) && $this->getFeaturedImage() != NULL)
            unlink ( $thumbPath );

    }

    public function removeUploadedFile($path)
    {
        $path =  $path.'/'.$this->getFile();
        //remove old file if exists
        if (file_exists( $path ) && $this->getFile() != NULL)
            unlink ( $path ) ;

    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Generates the magic method
     *
     */
    public function __toString()
    {
        // to show the name of the Post in the select
        return $this->title;
    }
}
