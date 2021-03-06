<?php /* #?ini charset="utf-8"?

[ViewCacheSettings]
ClearRelationTypes[]=common
ClearRelationTypes[]=reverse_common
ClearRelationTypes[]=reverse_embedded
ClearRelationTypes[]=reverse_attribute
SmartCacheClear=enabled

[forum_reply]
DependentClassIdentifier[]=forum_topic
DependentClassIdentifier[]=forum
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating
ClearCacheMethod[]=siblings

[forum_topic]
DependentClassIdentifier[]=forum
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating
ClearCacheMethod[]=siblings

[folder]
DependentClassIdentifier[]=folder
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating

[gallery]
DependentClassIdentifier[]=folder
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating

[image]
DependentClassIdentifier[]=gallery
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating
ClearCacheMethod[]=siblings

[event]
DependentClassIdentifier[]=event_calender
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating

[article]
DependentClassIdentifier[]=folder
DependentClassIdentifier[]=frontpage
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating

[article_mainpage]
DependentClassIdentifier[]=folder
DependentClassIdentifier[]=frontpage
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating

[article_subpage]
DependentClassIdentifier[]=article_mainpage
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating
ClearCacheMethod[]=siblings

[blog_post]
DependentClassIdentifier[]=frontpage
DependentClassIdentifier[]=blog
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating

[comment]
DependentClassIdentifier[]=frontpage
DependentClassIdentifier[]=blog
DependentClassIdentifier[]=blog_post
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating

[product]
DependentClassIdentifier[]=folder
DependentClassIdentifier[]=frontpage
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating

[infobox]
DependentClassIdentifier[]=folder
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating

[documentation_page]
DependentClassIdentifier[]=documentation_page
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating

[banner]
DependentClassIdentifier[]=frontpage
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating

[geo_article]
DependentClassIdentifier[]=frontpage
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating
*/ ?>