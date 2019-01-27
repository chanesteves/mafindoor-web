var tab = new Tab();

var default_tab_button_height_md = 40;
var default_tab_button_height_sm = 32;

var max_try = 100;
var count_try = 0;

function Tab(){
    
}

Tab.prototype.init = function () {
  var url = document.location.toString();
  var cur_url = url;
  if (url.match('#') && !url.match('#_=_')) {
      $('.nav-tabs a[href="#' + url.split(RegExp("([#])"))[2] + '"]').tab('show');
      
      cur_url = url.split(RegExp("([#])"))[1];

      var tab_panes;
      if ($('.nav-tabs a[href="#' + url.split(RegExp("([#])"))[2] + '"]').parent().parent().parent().find('> .tab-content > .tab-pane').length > 0)
        tab_panes = $('.nav-tabs a[href="#' + url.split(RegExp("([#])"))[2] + '"]').parent().parent().parent().find('> .tab-content > .tab-pane');
      else
        tab_panes = $('.nav-tabs a[href="#' + url.split(RegExp("([#])"))[2] + '"]').parent().parent().parent().parent().parent().find('> .tab-content > .tab-pane');

      tab_panes.removeClass('active');
      // $('#' + url.split('#')[1]).addClass('active');
      try {
         $('#' + url.split(RegExp("([#])"))[2]).addClass('active');
      }
      catch(error) {
        return false;
      }
  } 

  $('.nav-tabs li a').click( function(e) {
    history.pushState( null, null, cur_url + $(this).attr('href') );
  });

  $(document).on('click.bs.tab.data-api', '[data-toggle="tab"]', function (e) {
    e.preventDefault()
    $(this).tab('show');

    var href = $(this).attr('href').substring($(this).attr('href').lastIndexOf('#') + 1);

    $('.nav-tabs a[href=#' + href + ']').parent().parent().find('> li').removeClass('active');
    $('.nav-tabs a[href=#' + href + ']').parent().addClass('active');
  });
}

Tab.prototype.autoCollapse = function() {
  $('.nav-tabs-md').each(function() {
    var tabsHeight = $(this).innerHeight();

    if (tabsHeight > default_tab_button_height_md) {
      while(tabsHeight > default_tab_button_height_md) {
        var children = $(this).children('li:not(:last-child)');
        var count = children.size();
        
        if ($('.collapsed').length > 0)
          $(children[count-1]).prependTo($(this).find('.collapsed'));
        
        tabsHeight = $(this).innerHeight();
      }
    }
    else {
      while(tabsHeight <= default_tab_button_height_md && ($(this).find('.collapsed').children('li').size()>0) && count_try < max_try) {
        var collapsed = $(this).find('.collapsed').children('li');
        var count = collapsed.size();
        $(collapsed[0]).insertBefore($(this).children('li:last-child'));
        tabsHeight = $(this).innerHeight();
      }
      if (tabsHeight > default_tab_button_height_md) {
        tab.autoCollapse();
        count_try++;
      }
    }

    if ($(this).find('.collapsed').children('li').size() > 0)
      $(this).find('.last-tab').show();
    else
      $(this).find('.last-tab').hide();
  });  

  $('.nav-tabs-sm').each(function() {
    var tabsHeight = $(this).innerHeight();

    if (tabsHeight > default_tab_button_height_sm) {
      while(tabsHeight > default_tab_button_height_sm) {
        var children = $(this).children('li:not(:last-child)');
        var count = children.size();
        $(children[count-1]).prependTo($(this).find('.collapsed'));
        
        tabsHeight = $(this).innerHeight();
      }
    }
    else {
      while(tabsHeight <= default_tab_button_height_sm && ($(this).find('.collapsed').children('li').size()>0) && count_try < max_try) {
        var collapsed = $(this).find('.collapsed').children('li');
        var count = collapsed.size();
        $(collapsed[0]).insertBefore($(this).children('li:last-child'));
        tabsHeight = $(this).innerHeight();
      }
      if (tabsHeight > default_tab_button_height_sm) {
        tab.autoCollapse();
        count_try++;
      }
    }

    if ($(this).find('.collapsed').children('li').size() > 0)
      $(this).find('.last-tab').show();
    else
      $(this).find('.last-tab').hide();
  });  
};