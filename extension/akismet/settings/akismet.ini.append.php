<?php /* #?ini charset="utf-8"?

#This only works if you have an api, get it at: http://akismet.com/personal/
[AccountSettings]
APIKey=e445abc6cac8

[SiteSettings]
BlogURL=http://www.lifeisaspectrum.com

[InformationExtractorSettings]
# Lists all classes that should make use of the Akismet service
ExtractableClasses[]=forum_topic
ExtractableClasses[]=forum_reply
ExtractableClasses[]=comment

# Consists of the class identifier name with _AkismetSettings appended
# Maps the author, email, website and comment to eZ publish content class attributes identifiers
# Empty fields are allowed, currently supports the following datatypes:
# author, url, email, text line, text block, xml block, user 

[forum_topic_AkismetSettings]
AuthorAttribute=anon_user
EmailAttribute=notify_me
WebsiteAttribute=
BodyAttribute=message

[forum_reply_AkismetSettings]
AuthorAttribute=anon_user
EmailAttribute=notify_me
WebsiteAttribute=
BodyAttribute=message

[comment_AkismetSettings]
AuthorAttribute=author
EmailAttribute=notify_me
WebsiteAttribute=
BodyAttribute=message


*/ ?>