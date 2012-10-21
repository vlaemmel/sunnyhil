{* DO NOT EDIT THIS FILE! Use an override template instead. *}
{section show=$attribute.content.has_discount}
  {pdf(text, "Price"|i18n("design/standard/content/datatype")|wash(pdf))}:
  {pdf(strike, $attribute.content.inc_vat_price|l10n(currency)|wash(pdf))}
  {pdf(text, concat("Your price"|i18n("design/standard/content/datatype"), " ", $attribute.content.discount_price_inc_vat|l10n(currency), "\n")|wash(pdf))}:
  {pdf(text, concat("You save"|i18n("design/standard/content/datatype"), " ", 
                    sub($attribute.content.inc_vat_price,$attribute.content.discount_price_inc_vat)|l10n(currency), 
	  	    " ( ", $attribute.content.discount_percent, " % )" )|wash(pdf))}:
{section-else}
  {pdf(text, concat("Price"|i18n("design/standard/content/datatype"), " ", $attribute.content.inc_vat_price|l10n(currency), "\n" )|wash(pdf))}
{/section}