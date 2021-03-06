<script>

    $(function () {

        $('.version').on('change', function () {
            var version = $(this).val();
            var id = '<?= $label->xss($_GET['id']) ?>';
            $.ajaxSubmit({
                url: `/?g=Doc&m=Article&a=switchVersion&id=${id}&version=${version}`,
                skipAutoTips: true,
                success: function (res, dialogOption) {
                    if (res.status != 200) {
                        var d = dialog(dialogOption).showModal();
                        setTimeout(function () {
                            d.close().remove();
                        }, 3000);
                    } else {
                        window.location.reload();
                    }
                }
            })
        })

        var titleNavigation = function () {
            var i = 0;
            var parent = `.nav-${i}-H1`;
            var hasTitleNagContent = false;
            var firstHTagTitle = null;

            $('.am-article-bd').children().each(function (key) {

                if (['H1', 'H2', 'H3', 'H4', 'H5', 'H6'].includes($(this)[0].nodeName) == false) {
                    return;
                }

                if(firstHTagTitle == null){
                    firstHTagTitle = key;
                }
                var name = $(this).text();

                var ulStr = '<li class="nav-' + i + '-' + $(this)[0].nodeName + ' nav-index-'+$(this)[0].nodeName+'"><a href="#nav-' + i + '-' + $(this)[0].nodeName + '">' + name + '</a></li>';

                if ($(this)[0].nodeName == 'H1' || key == firstHTagTitle) {
                    $('.title-nav-content>ul').append('<li class="nav-' + i + '-' + $(this)[0].nodeName + ' nav-index-'+$(this)[0].nodeName+'"><a href="#nav-' + i + '-' + $(this)[0].nodeName + '">' + name + '</a></li>')

                    parent = `.nav-${i}-` + $(this)[0].nodeName;
                    $(this).attr('id', `nav-${i}-` + $(this)[0].nodeName)
                    i++;
                    hasTitleNagContent = true;
                } else {

                    var nodeName = $(this)[0].nodeName;


                    //?????????????????????
                    if (`.nav-${i}-` + nodeName == parent || parent.substr(-2) == nodeName ) {
                        i++;
                        $(parent).parent().append('<li class="nav-' + i + '-' + $(this)[0].nodeName + ' nav-index-'+$(this)[0].nodeName+' "><a href="#nav-' + i + '-' + $(this)[0].nodeName + '">' + name + '</a></li>')
                        parent = `.nav-${i}-` + $(this)[0].nodeName;

                    } else if (nodeName.substr(1) < parent.substr(-1)) {

                        i++;
                        if($('.nav-index-'+$(this)[0].nodeName).last().length > 0){
                            var element = $('.nav-index-'+$(this)[0].nodeName).last();
                        }else{
                            var element = $(parent).parent().parent('li')
                        }


                        element.after('<li class="nav-' + i + '-' + $(this)[0].nodeName + ' nav-index-'+$(this)[0].nodeName+'"><a href="#nav-' + i + '-' + $(this)[0].nodeName + '">' + name + '</a></li>')

                        parent = `.nav-${i}-` + $(this)[0].nodeName;

                    } else {

                        $(parent).append('<ul>' + ulStr + '</ul>')
                        parent = `.nav-${i}-` + $(this)[0].nodeName;
                    }
                    $(this).attr('id', `nav-${i}-` + $(this)[0].nodeName);
                    hasTitleNagContent = true;
                }
            })

            if(hasTitleNagContent == false){
                //??????????????????????????????????????????????????????
                $('.title-nav').hide();
                return;
            }else{
                //????????????????????????
                if($('.title-nav').css('display') == 'none'){
                    return;
                }

                var openWidth = $('.title-nav').attr('data') == '0' ? 0 : 300;

                $('.content').css({'margin-right': (openWidth + 50) +'px'});

                $('.title-nav').animate({width: openWidth + 'px'}, 500, function (){
                    if(openWidth > 0){
                        $(this).find('i').attr('class', 'am-icon-angle-double-right');
                    }


                    //???????????????????????????????????????????????????URL???????????????
                    if(window.location.hash.length > 0){
                        var anchorPoint = window.location.href;
                        window.location.href = anchorPoint;
                    }


                })
            }

            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    const id = entry.target.getAttribute('id');
                    if (entry.intersectionRatio > 0) {
                        document.querySelector(`.title-nav-content li a[href="#${id}"]`).parentElement.classList.add('active');
                    } else {
                        document.querySelector(`.title-nav-content li a[href="#${id}"]`).parentElement.classList.remove('active');
                    }
                });
            });

            document.querySelectorAll('h1[id], h2[id], h3[id], h4[id], h5[id], h6[id]').forEach((section) => {
                observer.observe(section);
            });
        }

        if ($('.use-md').val() == 1) {
            try{
                Vditor.preview(document.getElementsByClassName('am-article-bd')[0], `<?= str_replace('`', '\`',
                    htmlspecialchars_decode(str_replace($articleTemplate['replace'], $articleTemplate['md'], isset($article_content_md) ? $article_content_md : $doc['doc_content_md']))
                ) ?>`, {
                    after() {
                        titleNavigation();
                    },
                })
            }catch (e){
                var d = dialog({id:'submit-tips', zIndex: '9999', fixed:true, skin:'submit-warning', content: '<i class="am-icon-exclamation-circle"></i> MD??????????????????'});
                d.show();
                setTimeout(function (){
                    d.close();
                }, 3000)
            }
        } else {
            titleNavigation();
        }


        /**
         * ???????????????????????????
         */
        $(document).on('click', '.title-nav-hide', function () {
            var dom = $(this);
            var data = $(this).attr('data');

            if (data == 0) {
                $('.title-nav').animate({width: '0'}, 500, function () {
                    dom.attr({
                        'data': '1',
                        'title': '??????????????????'
                    })
                    dom.find('i').attr('class', 'am-icon-angle-double-left')
                    $('.content').css({'margin-right': '5%'});
                });
            } else {
                $('.title-nav').animate({width: '300px'}, 500, function () {
                    dom.attr({
                        'data': '0',
                        'title': '??????????????????'
                    })
                    dom.find('i').attr('class', 'am-icon-angle-double-right');
                    $('.content').css({'margin-right': '350px'});
                });
            }

        })

        //?????????????????????
        $('.sidebar-nav ul li').on('click', function () {
            var sidebarNavIDom = $(this).children('span').children('i')
            if (sidebarNavIDom.hasClass('am-icon-caret-right')) {
                sidebarNavIDom.removeClass('am-icon-caret-right').addClass('am-icon-caret-down');
                $(this).children('ul').children('li').removeClass('sidebar-hide');
            } else {
                sidebarNavIDom.addClass('am-icon-caret-right').removeClass('am-icon-caret-down');
                $(this).children('ul').children('li').addClass('sidebar-hide');
            }

            return false;
        })

        $('.sidebar-nav ul li a').on('click', function () {
            var target = $(this).attr('target')
            if(target){
                window.open($(this).attr('href'))
            }else{
                window.location.href = $(this).attr('href');
            }

        })

        $('.sidebar-nav ul li.am-active a').parents('li').each(function () {
            $(this).children('span').children('i').removeClass('am-icon-caret-right').addClass('am-icon-caret-down')
            $(this).siblings().removeClass('sidebar-hide')
            $(this).removeClass('sidebar-hide')
        })

        /**
         * ????????????
         */
        $(document).on('click', '.pes-like', function () {
            var dom = $(this);
            var num = parseInt($('.pes-like-num').html());
            $.ajaxSubmit({
                url: '/?g=Doc&m=Article&a=like',
                data: {id: '<?= $doc['doc_id'] ?>', aid: '<?= $article_mark ?>', method: 'PUT'},
                method: 'POST',
                skipAutoTips: true,
                stopJump: true,
                success: function (res, dialogOption) {
                    if(res.status == 200){
                        $('.pes-like-num').html( num + 1 )
                    }else{
                        var d = dialog(dialogOption);
                        d.show();
                        setTimeout(function () {
                            d.close();
                        }, 1800)
                    }
                }
            })
        })

        $('#pes-show-article-path').on('click', function () {
            $('.sidebar, .mask-layer').show()
        })
        $('.mask-layer').on('click', function () {
            $('.mask-layer').hide()
            $('.sidebar').animate({opacity: 0, width: '0px'}, 500, function () {
                $('.sidebar').removeAttr('style')
            })
        })

    })
</script>