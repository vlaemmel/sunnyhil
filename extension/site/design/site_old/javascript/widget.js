/* Configuration: */

var tabs = {
	"@facebook" : {
		"feed"		: "http://www.comsmart.org/StatusExport/rss.php?uid=1541255456",
		"function"	: none
	}, 
	"@twitter" : {
		"feed"		: "http://twitter.com/statuses/user_timeline/113436143.rss",
		"function"	: twitter
	}, 
	"@examiner" : {
		"feed"		: "http://rss.examiner.com/RSS-43368-Tallahassee-Motherhood-Examiner.rss",
		"function"	: rss
	}
}

var totalTabs;
$(document).ready(function(){
	/* This code is executed after the DOM has been completely loaded */
	
	/* Counting the tabs */
	totalTabs=0;
	$.each(tabs,function(){totalTabs++;})
	

	$('#feedWidget').show().mouseleave(function(){
		
		/* If the cursor left the widet, hide the drop down list: */
		$('.dropDownList').remove();


	}).mouseenter(function(){
		

		
	});

	$('#activeTab').click(showDropDown);

	/* Using the live method to bind an event, because the .dropDownList does not exist yet: */
	$('.dropDownList div').live('click',function(){
		
		/* Calling the showDropDown function, when the drop down is already shown, will hide it: */
		showDropDown();
		showTab($(this).text());
	});
	
	
	/* Showing one of the tabs on load: */
	showTab('@facebook');
	
});


function showTab(key)
{
	var obj = tabs[key];
	if(!obj) return false;
	
	var stage = $('#tabContent');
	
	/* Forming the query: */
	var query = "select * from feed where url='"+obj.feed+"' LIMIT 3";
	
	/* Forming the URL to YQL: */
	var url = "http://query.yahooapis.com/v1/public/yql?q="+encodeURIComponent(query)+"&format=json&callback=?";
	
	$.getJSON(url,function(data){

		stage.empty();

		/* item exists in RSS and entry in ATOM feeds: */
		$.each(data.query.results.item || data.query.results.entry,function(){
			try{
				/* Trying to call the user provided function, "this" the rest of the feed data: */
				stage.append(obj['function'](this));
				
			}
			catch(e){
				/* Notifying users if there are any problems with their handler functions: */
				var f_name =obj['function'].toString().match(/function\s+(\w+)\(/i);
				if(f_name) f_name = f_name[1];
				
				stage.append('<div>There is a problem with your '+f_name+ ' function</div>');
				return false;
			}
		})
	});
	
	$('#activeTab').text(key);
}

function showDropDown()
{
	if(totalTabs<2) return false;
	
	if($('#feedWidget .dropDownList').length)
	{
		/* If the drop down is already shown, hide it: */
		$('.dropDownList').slideUp('fast',function(){ $(this).remove(); })
		return false;
	}
	
	var activeTab = $('#activeTab');
	
	var offsetTop = (activeTab.offset().top - $('#feedWidget').offset().top )+activeTab.outerHeight() - 5;
	
	/* Creating the drop down div on the fly: */
	var dropDown = $('<div>').addClass('dropDownList').css({

			'top'	: offsetTop,
			'width'	: activeTab.width()
	
	}).hide().appendTo('#feedWidget')
	
	$.each(tabs,function(j){
		/* Populating the div with the tabs that are not currently shown: */
		if(j==activeTab.text()) return true;
		
			$('<div>').text(j).appendTo(dropDown);
	})
	
	dropDown.slideDown('fast');
}

function twitter(item)
{
	/* Formats the tweets, by turning hashtags, mentions an URLS into proper hyperlinks: */
	return $('<div>').html(
			formatString(item.description || item.title)+
			' <a href="'+(item.link || item.origLink)+'" target="_blank">[tweet]</a>'
	);
}

function rss(item)
{
	return $('<div>').html(
			formatString(item.title.content || item.title)+
			' <a href="'+(item.origLink || item.link[0].href || item.link)+'" target="_blank">[read]</a>'
	);
}

function buzz(item)
{
	return $('<div>').html(
			formatString(item.content[0].content || item.title.content || item.title)+
			' <a href="'+(item.origLink || item.link[0].href || item.link)+'" target="_blank">[read]</a>'
	);
}

function none(item)
{
	return $('<div>').html(formatString(item.title.content || item.title));
}

function formatString(str)
{
	/* This function was taken from our Twitter Ticker tutorial - http://tutorialzine.com/2009/10/jquery-twitter-ticker/ */
	str = str.replace(/<[^>]+>/ig,'');
	str=' '+str;
	str = str.replace(/((ftp|https?):\/\/([-\w\.]+)+(:\d+)?(\/([\w/_\.]*(\?\S+)?)?)?)/gm,'<a href="$1" target="_blank">$1</a>');
	str = str.replace(/([^\w])\@([\w\-]+)/gm,'$1@<a href="http://twitter.com/$2" target="_blank">$2</a>');
	str = str.replace(/([^\w])\#([\w\-]+)/gm,'$1<a href="http://twitter.com/search?q=%23$2" target="_blank">#$2</a>');
	return str;
}