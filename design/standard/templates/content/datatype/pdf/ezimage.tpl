{* DO NOT EDIT THIS FILE! Use an override template instead. *}

{default image_class=large
         alignment=false()
         hspace=false()
         border_size=0}
{let image_content=$attribute.content}
{section show=$image_content.original.is_valid}
  {let image=$image_content[$image_class]}

       {pdf(image,hash(src,$image.full_path,
                           width,$image.width,
                           height,$image.height,
                           border,$border_size,
                           align,$attribute_parameters.align))}

  {/let}
{/section}
{/let}
{/default}
