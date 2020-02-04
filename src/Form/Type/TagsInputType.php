<?php

namespace App\Form\Type;

use App\DataTransformer\TagsArrayToStringTransformer;
use App\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TagsInputType extends AbstractType
{
    /**
     * @var TagRepository
     */
    private $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addModelTransformer( new CollectionToArrayTransformer(), true)
            ->addModelTransformer( new TagsArrayToStringTransformer($this->tagRepository), true);
    }

    public function getParent()
    {
        return TextType::class;
    }
}