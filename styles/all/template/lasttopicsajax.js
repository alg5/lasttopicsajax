/* File Created: апреля 13, 2017 */
(function ($, document) { // Avoid conflicts with other libraries

    $("#lnkNewLinks").on('click', function (e) {
        e.preventDefault();

        $(this).hide();
        $("#loader_save").css('display', 'inline-block');
        var path = U_LASTTOPICSAJAX_PATH_POPUP;
         $.ajax({
            type: 'POST',
            url: path,
            success: function (data) {
                $("#loader_save").hide();
                $("#lastContent").html(data);
                $("#myModal").addClass("lt-modal_active").show();
                $('.topictitle').topicPreview({
                    dir: S_CONTENT_DIRECTION,
                    delay: TOPICPREVIEW_DELAY,
                    width: TOPICPREVIEW_WIDTH,
                    drift: TOPICPREVIEW_DRIFT,
                    position: { left: 35, top: 25 },
                    noavatar: T_THEME_PATH + '/images/no_avatar.gif'
                });
                $("#closeNew").show().on("click", function (e) {
                    $("#lnkNewLinks").show();
                    $("#myModal").removeClass("lt-modal_active").hide();
                });
            }
        });
    });
    var pgn_links = $(".lt-pagination a");
    $("#wrap").on("click", ".lt-pagination a", function (e) { 
        console.log(this);
        console.log($(this).parent());
    
        if ( $(this).parent().hasClass('dropdown-trigger') ) {
            return true;
        }
        e.preventDefault();
        if($(this).hasClass('lt-pagination-go'))
            return;
        var col = $(this).parent().parent().parent().attr('data-col')
        var old_page = $(this).parent().parent().find('li.active');
        var old_page_value = $(this).parent().parent().find('li.active').text();
        var path = $(this).attr('href');
        path += path.indexOf('?') > -1 ? '&' : '?';
        path += 'col=' + col;
        path += '&old_page=' + old_page_value;
        paginationByPage(col, path);
        return;
    });

    $("#wrap").on("click", ".lt-pagination-go", function (e) {
        e.preventDefault();
        var new_page = $(this).parent().find('input').val();
        var total_pages = parseInt($(this).attr("data-total-pages"));
        if (isNaN(new_page))
            return;
        new_page = parseInt(new_page);
        if(new_page < 1 || new_page > total_pages)
            return;
        var start = new_page * (S_ROWS_AMOUNT - 1);
        var col = $(this).attr("data-col");
        var old_page = $("#lt_pagination_" + col).find("li.active span").text();
             // console.log($(this).parent());
        console.log(old_page);
        // console.log($("#lt_block_" + col).find("li.active span"));
        // console.log($(this).parent().find(input).value);
        // console.log($(this).attr("href"));
        // console.log($(this).attr("data-col"));
        var path = $(this).attr('href');
        path += path.indexOf('?') > -1 ? '&' : '?';
        path += 'start=' + start;
        path += '&col=' + col;
        path += '&old_page=' + old_page;
        console.log("path123 =" + path);
        paginationByPage(col, path);

   });
    $("#wrap").on("click", ".lt-markread", function (e) {
        e.preventDefault();
        var col = $(this).attr('data-col-markread');
        var current_page_value = $(this).parent().find('li.active').text();
        var active_pages = $(this).parents().find('li.active');
        $(active_pages).each(function (index, item) {
            if ($(item).attr('data-col-pagenum') == col) {
                current_page_value = $(item).text();
            }
        });
        var path = U_LASTTOPICSAJAX_PATH_MARKREAD;
        path += path.indexOf('?') > -1 ? '&' : '?';
        path += 'col=' + col;
        path += '&curr_page=' + current_page_value;
        $.ajax({
            type: 'POST',
            url: path,
            success: function (data) {
                var arr_lnk1 = $('a[data-lt1_column=' + col + ']');
                var arr_lnk2 = $('a[data-lt2_column=' + col + ']');
                
                $(data.rowset).each(function (index, item) {
                    if (item.S_UNREAD_TOPIC) {
                        var U_NEWEST_POST = item.U_NEWEST_POST.toString().replace(/&amp;/g, "&");
                        $(arr_lnk1).eq(index).attr('href', U_NEWEST_POST);
                        $(arr_lnk2).eq(index).attr('href', U_NEWEST_POST);
                        $(arr_lnk1).eq(index).html(data.NEWEST_POST_IMG);
                    }
                    else {
//                        console.log($(arr_lnk1).eq(index))
//                        console.log($(arr_lnk2).eq(index))
                         $('span[data-topic_id=' + item.TOPIC_ID + ']').css('visibility', 'hidden');
                         
                        var U_LAST_POST = item.U_LAST_POST.toString().replace(/&amp;/g, "&");
 
                        $(arr_lnk1).eq(index).attr('href', U_LAST_POST).removeClass('unread');
                        $(arr_lnk2).eq(index).attr('href', U_LAST_POST);
                        $(arr_lnk1).eq(index).find('i').removeClass('icon-red').addClass('icon-lightgray');
                    }
                    $(arr_lnk2).eq(index).attr('title', item.FULL_TITLE);
                    $(arr_lnk2).eq(index).html(item.TITLE + '(' + item.TOPIC_REPLIES + ')');
                });
            }
        });
    });
    $("#wrap").on("click", ".lt-marktopicread", function (e) {
        e.preventDefault();
        var topic_id = $(this).attr('data-topic_id');
        var forum_id = $(this).attr('data-forum_id');
        var path = U_LASTTOPICSAJAX_PATH_TOPICMARKREAD;
        path += path.indexOf('?') > -1 ? '&' : '?';
        path += 'topic_id=' + topic_id;
        path += '&forum_id=' + forum_id;
        $.ajax({
            type: 'POST',
            url: path,
            success: function (data) {
                var item = $('span[data-topic_id=' + data.TOPIC_ID + ']');
                var U_LAST_POST = data.U_LAST_POST.toString().replace(/&amp;/g, "&");
                $(item).next().next().attr('href', U_LAST_POST);
                $(item).next().attr('href', U_LAST_POST).removeClass('unread');
                $(item).next().find('i').removeClass('icon-red').addClass('icon-lightgray');
                $(item).css('visibility', 'hidden');
 
            }
        });
    });

    function paginationByPage(col, path) {
        console.log("paginationByPage:path = " + path)
        $('div[data-col=' + col + ']').addClass('lt-inactive');
//        console.log($('div[data-col=' + col + ']').find('div.lt-loader_pagination'));
        $.ajax({
            type: 'POST',
            url: path,
            success: function (data) {
                $('div[data-col=' + col + ']').html(data.pagination_block);
                var arr_lnk1 = $('a[data-lt1_column=' + col + ']');
                var arr_lnk2 = $('a[data-lt2_column=' + col + ']');
                var prev = $('li[data-col-prev=' + col + ']');
                var next = $('li[data-col-next=' + col + ']');
                $(data.rowset).each(function (index, item) {
                    if (item.S_UNREAD_TOPIC) {
                        var U_NEWEST_POST = item.U_NEWEST_POST.toString().replace(/&amp;/g, "&");
                        $(arr_lnk1).eq(index).attr('href', U_NEWEST_POST);
                        $(arr_lnk2).eq(index).attr('href', U_NEWEST_POST);
                        $(arr_lnk1).eq(index).html(item.NEWEST_POST_IMG);
                    }
                    else {
                        var U_LAST_POST = item.U_LAST_POST.toString().replace(/&amp;/g, "&");
                        $(arr_lnk1).eq(index).attr('href', U_LAST_POST);
                        $(arr_lnk2).eq(index).attr('href', U_LAST_POST);
                        $(arr_lnk1).eq(index).html(item.LATEST_POST_IMG);
                    }
                    $(arr_lnk2).eq(index).attr('title', item.FULL_TITLE);
                    $(arr_lnk2).eq(index).html(item.TITLE + '(' + item.TOPIC_REPLIES + ')');

                    //change preview
                    var preview = $(arr_lnk2).eq(index).parent().find('div.topic_preview_content');
                    $(preview).find('div.topic_preview_first_avatar').html(item.TOPIC_PREVIEW_FIRST_AVATAR);
                    $(preview).find('div.topic_preview_last_avatar').html(item.TOPIC_PREVIEW_LAST_AVATAR);
                    $(preview).find('div.topic_preview_first').html(item.TOPIC_PREVIEW_FIRST_POST);
                    $(preview).find('div.topic_preview_last').html(item.TOPIC_PREVIEW_LAST_POST);
                });
                $('div[data-col=' + col + ']').removeClass('lt-inactive')
                return;
                $("#loader_save").hide();
                $("#lastContent").html(data);
                $("#myModal").show();
                $("#closeNew").show().on("click", function (e) {
                    $("#lnkNewLinks").show();
                    $("#myModal").hide();
                });
            }
        });
    }
//******************************************** 
	/**
	 * Adds Ajax functionality for the pagination.
	 */
	// quickreply.style.bindPagination = function() {
	// 	if (quickreply.settings.saveReply) {
	// 		$('.action-bar .pagination a:not(.dropdown-trigger, .mark[href="#unread"])').click(function(event) {
	// 			event.preventDefault();
	// 			quickreply.ajaxReload.loadPage($(this).attr('href'));
	// 		});
	// 	}

	// 	$('.action-bar .pagination a.mark[href="#unread"]').click(function(event) {
	// 		event.preventDefault();
	// 		var $unreadPosts = $('.unreadpost');
	// 		quickreply.functions.softScroll(($unreadPosts.length) ? $unreadPosts.first() : quickreply.$.qrPosts);
	// 	});

	// 	$('.action-bar .pagination .page-jump-form :button').click(function() {
	// 		var $input = $(this).siblings('input.inputbox');
	// 		quickreply.functions.pageJump($input);
	// 	});

	// 	$('.action-bar .pagination .page-jump-form input.inputbox').on('keypress', function(event) {
	// 		if (event.which === 13 || event.keyCode === 13) {
	// 			event.preventDefault();
	// 			quickreply.functions.pageJump($(this));
	// 		}
	// 	});

	// 	$('.action-bar .pagination .dropdown-trigger').click(function() {
	// 		var $dropdownContainer = $(this).parent();
	// 		// Wait a little bit to make sure the dropdown has activated
	// 		setTimeout(function() {
	// 			if ($dropdownContainer.hasClass('dropdown-visible')) {
	// 				$dropdownContainer.find('input.inputbox').focus();
	// 			}
	// 		}, 100);
	// 	});
	// };

	// /**
	//  * Adds initial Ajax functionality for the pagination.
	//  */
	// quickreply.style.initPagination = function() {
	// 	$(window).on('load', function() {
	// 		$('.action-bar .pagination').find('.page-jump-form :button').unbind('click');
	// 		$('.action-bar .pagination').find('.page-jump-form input.inputbox').off('keypress');
	// 		quickreply.style.bindPagination();
	// 	});
	// };

//********************************************
})(jQuery, document);                                                                       // Avoid conflicts with other libraries
