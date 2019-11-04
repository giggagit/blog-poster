/* start: Admin sidebar */
$(document).ready(function () {
    $(window).resize(function () {
        if ($(window).width() >= 991) {
            $('.sidebar').slideDown(350);
        }
    });
});

$(document).ready(function () {
    $('.sub-menu > a').click(function (e) {
        e.preventDefault();
        var menu_li = $(this).parent('li');
        var menu_ul = $(this).next('ul');

        if (menu_li.hasClass('open')) {
            menu_ul.slideUp(350);
            menu_li.removeClass('open');
        } else {
            $('.nav > li > ul').slideUp(350);
            $('.nav > li').removeClass('open');
            menu_ul.slideDown(350);
            menu_li.addClass('open');
        }
    });
});

$(document).ready(function () {
    $('.sidebar-dropdown a').on('click', function (e) {
        e.preventDefault();
        if (!$(this).hasClass('expand')) {
            // hide any open menus and remove all other classes
            $('.sidebar').slideUp(350);
            $('.sidebar-dropdown a').removeClass('expand');

            // open our new menu and add the expand class
            $('.sidebar').slideDown(350);
            $(this).addClass('expand');
        } else if ($(this).hasClass('expand')) {
            $(this).removeClass('expand');
            $('.sidebar').slideUp(350);
        }
    });
});

/* end: Admin sidebar navigation */

/* ********************************************************** */

/* start: Scroll to Top */

$('.to-top').hide();

$(function () {
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.to-top').slideDown();
        } else {
            $('.to-top').slideUp();
        }
    });

    $('.to-top a').click(function (e) {
        e.preventDefault();
        $('body,html').animate({
            scrollTop: 0
        }, 500);
    });
});

/* end: Scroll to top */

/* ************************************** */

/* start: Summernote */

$(document).ready(function () {
    $('.summernote').summernote({
        height: 300,
        tabsize: 4,
        toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline', 'strike']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['picture', 'link', 'video']],
            ['view', ['fullscreen', 'codeview']],
            ['help', ['help']]
        ]
    });
});

var postForm = function () {
    var content = $('textarea[name="post-template"]').val($('.summernote').code());
};

/* end: Summernote */

/* ************************************** */

/* start: Select all */

$(document).ready(function () {
    $(':checkbox.select-all').on('click', function () {
        $(':checkbox[name="' + $(this).data('checkbox-name') + '"]').prop('checked', $(this).prop('checked'));
    });

    $(':checkbox.checkme').on('click', function () {
        var _selectall = $(this).prop('checked');
        if (_selectall) {
            $(':checkbox[name="' + $(this).attr('name') + '"]').each(function (i) {
                _selectall = $(this).prop('checked');
                return _selectall;
            });
        }

        $(':checkbox[name="' + $(this).data('select-all') + '"]').prop('checked', _selectall);
    });

    $('input[name="select-all"').click(function () {
        $('select[name="template[]"] option').prop('selected', 'selected');
    });
});

/* end: Select all */

/* ************************************** */

/* start: Latest tab */

/*$(function() {
    //for bootstrap 3 use 'shown.bs.tab' instead of 'shown' in the next line
    $('a[data-toggle="tab"]').on('click', function (e) {
        //save the latest tab; use cookies if you like 'em better:
        localStorage.setItem('lastTab', $(e.target).attr('href'));
    });

    //go to the latest tab, if it exists:
    var lastTab = localStorage.getItem('lastTab');

    if (lastTab) {
        $('a[href="'+lastTab+'"]').click();
    }
});*/

/* end: Latest tab */

/* ************************************** */

/* start: Tooltip */

$(document).ready(function () {
    $('[data-toggle=tooltip]').tooltip({
        placement: 'top'
    });
});

/* end: Tooltip */

/* ************************************** */

/* start: Static modal */

//$('#select-delete').modal({'backdrop': 'static'});

/* end: Static modal */

/* ************************************** */

/* start: Alert close button */

$('.alert').alert();

/* end: Alert close button */

/* ************************************** */

/* start: Passing data to modal */

$(document).on('click', '.deleteItem', function () {
    var deleteID = $(this).data('id');
    $('.modal-body #itemID').val(deleteID);
});

$(document).on('click', '.deleteLog', function () {
    var deleteID = $(this).data('id');
    $('.modal-body #logID').val(deleteID);
});

$(document).on('click', '.deleteBlog', function () {
    var deleteID = $(this).data('id');
    $('.modal-body #blogID').val(deleteID);
});

/* end: Passing data to modal */

/* ************************************** */

/* start: Datepicker for bootstrap */

$("input[name='start-post']").datepicker({
    format: 'yyyy-mm-dd'
});

$("input[name='end-post']").datepicker({
    format: 'yyyy-mm-dd'
});

/* end: Datepicker for bootstrap */

/* ************************************** */

/* start: Multiple dynamic input text */

$(document).ready(function () {
    $("input[id^='option']").on('keyup change click', function() {
       var match = $(this).attr("id").match(/^(optionName|optionValue)-([0-9]+)$/);
       $('#optionValue-' + match[2]).attr('name', 'options[' + $('#optionName-' + match[2]).val() + ']');
    });

    $('.remove').click(function () {
        $(this).closest('.row').remove();
    });

    $('.more-option').click(function () {
        var intId = $('#options .row').length + 1;
        var divRow = $('<div class="row">');
        var divCol12 = $('<div class="col-md-12">');
        var divGroup = $('<div class="form-group">');
        var divName = $('<div class="col-md-3">');
        var divInput = $('<div class="col-md-3">');
        var divRemove = $('<div class="col-md-2">');
        var labelName = $('<label class="col-md-2 control-label">Option Name</label>');
        var inputName = $('<input type="text" class="form-control" id="optionName-' + intId + '" placeholder="Option name" required />');
        var labelValue = $('<label class="col-md-2 control-label">Option Value</label>');
        var inputValue = $('<input type="text" class="form-control" id="optionValue-' + intId + '" placeholder="Option Value" required />');
        var removeButton = $('<button type="button" class="btn btn-xs red remove"><i class="fa fa-times"></i> Remove Option</button>');

        inputName.on('keyup change click', function () {
            $('#optionValue-' + intId).attr('name', 'options[' + $('#optionName-' + intId).val() + ']');
        });

        removeButton.click(function () {
            $(this).closest('.row').remove();
        });

        divName.append(inputName);
        divInput.append(inputValue);
        divRemove.append(removeButton);
        divGroup.append(labelName);
        divGroup.append(divName);
        divGroup.append(labelValue);
        divGroup.append(divInput);
        divGroup.append(divRemove);
        divCol12.append(divGroup);
        divRow.append(divCol12);
        $("#options").append(divRow);
    });
});

/* end: Multiple dynamic input text */

/* ************************************** */

/* start: Preview button */

$(document).ready(function () {
    form = document.getElementById('template');
    $('.preview').click(function () {
        form.target = 'popup';
        form.action = '?module=preview';
        form.submit();
    });

    $('.submit').click(function () {
        form.target = '';
        form.action = '?module=template';
        form.submit();
    });
});

/* end: Preview button */

/* ************************************** */

$(function () {
    var d1 = [];
    var d2 = [];
    var stack = 0;
    var bars = true;
    var lines = false;
    var steps = false;

    for (var i = 0; i <= 35; i += 1)
        d1.push([i, parseInt(Math.random() * 30)]);
    for (var i = 0; i <= 35; i += 1)
        d2.push([i, parseInt(Math.random() * 30)]);

    function plotWithOptions() {
        $.plot($("#dashboard-chart"), [d1, d2], {
            series: {
                stack: stack,
                lines: {show: lines, fill: true, steps: steps},
                bars: {show: bars, barWidth: 0.8}
            },
            grid: {borderWidth: 0, hoverable: true, color: "#777"},
            colors: ["#16cbe6", "#0fa6bc"],
            bars: {
                show: true,
                lineWidth: 0,
                fill: true,
                fillColor: {colors: [{opacity: 0.9}, {opacity: 0.8}]}
            }
        });
    }

    plotWithOptions();
    $(".stackControls input").click(function (e) {
        e.preventDefault();
        stack = $(this).val() == "With stacking" ? true : null;
        plotWithOptions();
    });

    $(".graphControls input").click(function (e) {
        e.preventDefault();
        bars = $(this).val().indexOf("Bars") != -1;
        lines = $(this).val().indexOf("Lines") != -1;
        steps = $(this).val().indexOf("steps") != -1;
        plotWithOptions();
    });
});
