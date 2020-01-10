/* File Created: апреля 13, 2017 */
(function ($, document) { // Avoid conflicts with other libraries



    $("#lnkNewLinks").on('click', function (e) {
        e.preventDefault();

        $(this).hide();
        $("#loader_save").css('display', 'inline-block');
        var path = U_LASTTOPICSAJAX_PATH_POPUP;
        //alert('path = ' + path);
        $.ajax({
            type: 'POST',
            //dataType: 'html',
            url: path,
            success: function (data) {
                // alert('success');
                //console.log(data);
                //                console.log($(".modal"));
                //                console.log($("#lastContent"));
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
//                 console.log($("#closeNew"));
                $("#closeNew").show().on("click", function (e) {
                    $("#lnkNewLinks").show();
                    $("#myModal").removeClass("lt-modal_active").hide();
                    //                    $("#topic_preview").detach().appendTo("#lastTopicBlock");
                    //                    console.log($("#lastTopicBlock"));

                });
                //                window.onclick = function (event) {
                //                    if (event.target == modal) {
                //                        $("#lnkNewLinks").show();
                //                        $(".modal").hide();
                //                    }
                //                }


                //                $("#loader_save").hide();
                //output_info_new(data.MESSAGE, 'warning');
            }
        });
    });
    var pgn_links = $(".lt-pagination a");
    // console.log(pgn_links);
    $("#wrap").on("click", ".lt-pagination a", function (e) {
        if ($(this).parent().hasClass('dropdown-trigger')) {
            alert('dropdown-trigger');
            return true;
        }
        e.preventDefault();
        //alert($(this).attr('href'));
        var col = $(this).parent().parent().parent().attr('data-col')
        var old_page = $(this).parent().parent().find('li.active');
        var old_page_value = $(this).parent().parent().find('li.active').text();
        //        console.log($(this).parent().parent());
        //        console.log('old_page_value = ' + old_page_value);

        //get current page
        //        var current_page_value = $(this).text();
        //        var current_page = $(this).parent();
        // console.log(current_page);
        //console.log('current_page_value = ' + current_page_value);

        var path = $(this).attr('href');
        path += path.indexOf('?') > -1 ? '&' : '?';
        path += 'col=' + col;
        path += '&old_page=' + old_page_value;
        paginationByPage(col, path);
        return;

        //path += '&new_page=' + new_page_value;
        // alert(path);
        $.ajax({
            type: 'POST',
            //dataType: 'html',
            url: path,
            success: function (data) {
                //alert('success');
                //                console.log(data);
                //                console.log($('a[data-lt1_column=' + col + ']'));
                //console.log($('div[data-col=' + col + ']'));
                $('div[data-col=' + col + ']').html(data.pagination_block);
                //               console.log($('td[data-topics=' + col + ']'));
                //                $('td[data-topics=' + col + ']').html(data.topics_block);
                //                return;

                var arr_lnk1 = $('a[data-lt1_column=' + col + ']');
                var arr_lnk2 = $('a[data-lt2_column=' + col + ']');
                var prev = $('li[data-col-prev=' + col + ']');
                var next = $('li[data-col-next=' + col + ']');
                // console.log(prev);
                // console.log(next);
                // var arr_preview = $(this).parent().parent().parent().find(".topic_preview_content"); 

                $(data.rowset).each(function (index, item) {
                    // console.log(index + ": " + index);
                    //console.log(item);
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
                return;
                //change pagination 
                //previouse 
                //button previouse
                if (data.U_PREV_PAGE == '') {
                    //button prev hide
                    if ($(prev).length != 0) {
                        //remove button
                        $(prev).remove();
                    }
                    else {
                        //do nothing
                    }
                }
                else {
                    //button prev visible
                    if ($(prev).length != 0) {
                        //change href
                        $(prev).find('a').attr('href', data.U_PREV_PAGE);
                    }
                    else {
                        var btn_prev = '<li class="previous" data-col-prev="' + col + '"><a href="' + data.U_PREV_PAGE + '" rel="prev" role="button">' + L_PREVIOUS + '.</a></li>';
                        //find button for 1-st page
                        var first_page = $('li[data-col-pagenum=' + col + ']').first();
                        //console.log(first_page);
                        $(btn_prev).insertBefore(first_page);
                    }
                }
                //33333333333333
                //button next
                if (data.U_NEXT_PAGE == '') {
                    //button next remove
                    $(next).remove();
                }
                else {
                    //button prev visible
                    if ($(next).length != 0) {
                        //change href
                        $(next).find('a').attr('href', data.U_NEXT_PAGE);
                    }
                    else {
                        var btn_next = '<li class="next" data-col-next="' + col + '"><a href="' + data.U_NEXT_PAGE + '" rel="next" role="button">' + L_NEXT + '.</a></li>';
                        //find button for last page
                        var last_page = $('li[data-col-pagenum=' + col + ']').last();
                        $(btn_next).insertBefore(last_page);
                    }
                }


                //33333333333333
                $(old_page).removeClass('active').html('<a href="' + data.U_OLD_PAGE + '" role="button">' + old_page_value + '</a>');
                $(current_page).addClass('active').html('<span>' + current_page_value + '</span>');
                //<li class="previous"><a href="./index.php" rel="prev" role="button">Пред.</a></li>




                return;





                //                console.log($(".modal"));
                //                console.log($("#lastContent"));
                $("#loader_save").hide();
                $("#lastContent").html(data);
                $("#myModal").show();
                //console.log($("#closeNew"));
                $("#closeNew").show().on("click", function (e) {
                    $("#lnkNewLinks").show();
                    $("#myModal").hide();
                });
            }
        });




    });


    $("#wrap").on("click", ".lt-markread", function (e) {
        e.preventDefault();
        var col = $(this).attr('data-col-markread');
        var current_page_value = $(this).parent().find('li.active').text();
        //console.log($(this).parent().find('li.active'));
        //console.log($(this).parent().parent().parent().parent().parent());
        //        console.log($(this).parents().find('li.active'));
        //        console.log($(this).parents().find('.lt-pagination'));
        //        //console.log($(this).parents().find('.lt-pagination').find("[data-col='" + col + "']")   );
        //       console.log($(this).parents().find('li.active').find('[data-col-pagenum="' + col + '"]'));
        var active_pages = $(this).parents().find('li.active');
        $(active_pages).each(function (index, item) {
            //console.log($(item).attr('data-col-pagenum'));
            if ($(item).attr('data-col-pagenum') == col) {
                current_page_value = $(item).text();
            }
        });

        //console.log($(active_pages).find('[data-col-pagenum="' + col + '"]'));
        //alert(current_page_value);
        var path = U_LASTTOPICSAJAX_PATH_MARKREAD;
        path += path.indexOf('?') > -1 ? '&' : '?';
        path += 'col=' + col;
        path += '&curr_page=' + current_page_value;
        $.ajax({
            type: 'POST',
            //dataType: 'html',
            url: path,
            success: function (data) {
                var arr_lnk1 = $('a[data-lt1_column=' + col + ']');
                var arr_lnk2 = $('a[data-lt2_column=' + col + ']');
                
                $(data.rowset).each(function (index, item) {
                    // console.log(index + ": " + index);
                    //console.log(item);
                    //remove style vigible for chrckbox
                   
                    if (item.S_UNREAD_TOPIC) {
                        console.log('111');
                        var U_NEWEST_POST = item.U_NEWEST_POST.toString().replace(/&amp;/g, "&");
                        $(arr_lnk1).eq(index).attr('href', U_NEWEST_POST);
                        $(arr_lnk2).eq(index).attr('href', U_NEWEST_POST);
                        $(arr_lnk1).eq(index).html(data.NEWEST_POST_IMG);
                    }
                    else {
                        console.log('222: index = ' + index);
                        console.log($(arr_lnk1).eq(index))
                        console.log($(arr_lnk2).eq(index))
                         $('span[data-topic_id=' + item.TOPIC_ID + ']').css('visibility', 'hidden');
                         
                        var U_LAST_POST = item.U_LAST_POST.toString().replace(/&amp;/g, "&");
 
                        $(arr_lnk1).eq(index).attr('href', U_LAST_POST).removeClass('unread');
                        $(arr_lnk2).eq(index).attr('href', U_LAST_POST);
                        //$(arr_lnk1).eq(index).html(data.LATEST_POST_IMG);
                        $(arr_lnk1).eq(index).find('i').removeClass('icon-red').addClass('icon-lightgray');
                    }
                    $(arr_lnk2).eq(index).attr('title', item.FULL_TITLE);
                    $(arr_lnk2).eq(index).html(item.TITLE + '(' + item.TOPIC_REPLIES + ')');
                });
//*******************************
//<span  style="visibility:hidden;" data-topic_id="{latest_topics_2.TOPIC_ID}" data-forum_id="{latest_topics_2.FORUM_ID}" class="lt-marktopicread" title="{L_MARK_READ}"><i class="fa fa-check-square-o" aria-hidden="true"></i></span>
//                                                    <a   data-lt1_column="2" href="{latest_topics_2.U_LAST_POST}">
//                                                            <i class="icon fa-file fa-fw icon-lightgray icon-md" aria-hidden="true"></i>
//                                                    </a>
//                                                    <a  data-lt2_column="2" class="topictitle" style='font-weight:normal;' href="{latest_topics_2.U_LAST_POST}" title="{latest_topics_2.FULL_TITLE}">{latest_topics_2.TITLE} ({latest_topics_2.TOPIC_REPLIES})</a>
//*******************************


                //alert('success1');
                console.log(data);
                //                console.log($(".modal"));
                //                console.log($("#lastContent"));
                //                $("#loader_save").hide();
                //                $("#lastContent").html(data);
                //                $("#myModal").show();
                //console.log($("#closeNew"));
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
        //*******************   
        //path += '&new_page=' + new_page_value;
        // alert(path);
        //$("#lastTopicsBlock").addClass('lt-inactive');
        //$('div[data-col=' + col + ']').addClass('lt-inactive').find('div.lt-loader_pagination').css('display', 'inline-block');
        //$('div[data-col=' + col + ']').hide('slow');
        $('div[data-col=' + col + ']').addClass('lt-inactive');
        //$('div[data-col=' + col + ']').find('div.lt-loader_pagination').show('slow');
        console.log($('div[data-col=' + col + ']').find('div.lt-loader_pagination'));
        $.ajax({
            type: 'POST',
            //dataType: 'html',
            url: path,
            success: function (data) {
                //alert('success');

                //                console.log(data);
                //                console.log($('a[data-lt1_column=' + col + ']'));
                //console.log($('div[data-col=' + col + ']'));
                $('div[data-col=' + col + ']').html(data.pagination_block);
                //               console.log($('td[data-topics=' + col + ']'));
                //                $('td[data-topics=' + col + ']').html(data.topics_block);
                //                return;

                var arr_lnk1 = $('a[data-lt1_column=' + col + ']');
                var arr_lnk2 = $('a[data-lt2_column=' + col + ']');
                var prev = $('li[data-col-prev=' + col + ']');
                var next = $('li[data-col-next=' + col + ']');
                // console.log(prev);
                // console.log(next);
                // var arr_preview = $(this).parent().parent().parent().find(".topic_preview_content"); 

                $(data.rowset).each(function (index, item) {
                    // console.log(index + ": " + index);
                    //console.log(item);
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
                // $("#lastTopicsBlock").removeClass('lt-inactive');
                $('div[data-col=' + col + ']').removeClass('lt-inactive')
                //$('div[data-col=' + col + ']').find('div.lt-loader_pagination').hide();
                //$('div[data-col=' + col + ']').show('slow');
                return;




                //                console.log($(".modal"));
                //                console.log($("#lastContent"));
                $("#loader_save").hide();
                $("#lastContent").html(data);
                $("#myModal").show();
                //console.log($("#closeNew"));
                $("#closeNew").show().on("click", function (e) {
                    $("#lnkNewLinks").show();
                    $("#myModal").hide();
                });
            }
        });

        //*******************   


    }


})(jQuery, document);                                                                       // Avoid conflicts with other libraries
