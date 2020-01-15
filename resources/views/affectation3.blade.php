<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src='https://bevacqua.github.io/dragula/dist/dragula.js'></script>


<html>
<head>
</head>
<body>
<div class="parent">
    <div class="wrapper">
        <div id="drag-elements" class="container">
            <div>Something 1</div>
            <div>Something 2</div>
            <div>Something 3</div>
            <div>Something 4</div>
            <div>Something 5</div>
            <div>Something 6</div>
            <div>Something 7</div>
            <div>Something 8</div>
            <div>Something 9</div>
        </div>
        <div id="drag-elements2" class="container">
            <div>Something A</div>
            <div>Something B</div>
            <div>Something C</div>
            <div>Something D</div>
            <div>Something E</div>
            <div>Something F</div>
            <div>Something G</div>
            <div>Something H</div>
            <div>Something I</div>
        </div>
    </div>
</div>
</body>
</html>

<script>


    $(document).ready(function () {
        'use strict';

        // global references

        var cont = $('.container'),
            hasMultiple = false,    // flags if there are multiple selection
            selectedItems,          // the multiple selections
            mirrorContainer,        // the floating preview
            shiftIsPressed = false;  // shift key on keyboard

        // setup draggable containers
        var drake =       dragula([
            <?php
       foreach($users as $user)
             {
                  if($user->isOnline()) {
                      echo "document.getElementById('user-".$user->id."'),";
                  }
             }

            ?>
document.getElementById('drag-elements'),
  document.getElementById('drag-elements2'),
  document.getElementById('drag-elements3'),
  document.getElementById('drag-elements4'),
  document.getElementById('drag-elements5')


        ], {
            revertOnSpill: true
        });

        // handle events
        drake.on('drag', (el) => {
            // nothing happening here
        })
        .on('cloned', (clone, original, type) => {

            // are we dragging from left to right?
            var isFromLeft =
                ($(original).parent().attr('id') == 'drag-elements')
                || ($(original).parent().attr('id') == 'drag-elements2')
                || ( $(original).parent().attr('id') == 'drag-elements3')
                || ( $(original).parent().attr('id') == 'drag-elements4')
                || ( $(original).parent().attr('id') == 'drag-elements5')
        ;


        // we're dragging from left to right
        if (isFromLeft) {

            // grab the mirror container dragula creates by default
            mirrorContainer = $('.gu-mirror').first();

            // multi selected items will have this class, but we don't want it on the ones in the mirror
            mirrorContainer.removeClass('selectedItem');

            // get the multi selected items
            selectedItems = $('.selectedItem');

            // do we have multiple items selected?
            // (takes into account edge case where they start dragging from an item that hasn't been selected)
            hasMultiple = selectedItems.length > 1 || (selectedItems.length == 1 && !$(original).hasClass('selectedItem'));

            // we have multiple items selected
            if(hasMultiple) {

                // edge case: if they started dragging from an unselected item, adds the selected item class
                $('.gu-transit').addClass('selectedItem');

                // update list of selected items in case of edge case above
                selectedItems = $('.selectedItem');

                // clear the mirror container, we're going to fill it with clones of our items
                mirrorContainer.empty();

                // will track final dimensions of the mirror container
                var height = 0,
                    width = 0;

                // clone the selected items into the mirror container
                selectedItems.each(function(index) {
                    // the item
                    var item = $(this);

                    // clone the item
                    var mirror = item.clone(true);

                    // remove the state classes if necessary
                    mirror.removeClass('selectedItem gu-transit');

                    //add the clone to mirror container
                    mirrorContainer.append(mirror);
                    mirrorContainer.css('background-color', 'transparent');

                    //add drag state class to item
                    item.addClass('gu-transit');

                    // update the dimensions for the mirror container
                    var rect = item[0].getBoundingClientRect();
                    height += rect.height;
                    width = rect.width;
                });

                //set final height of mirror container
                mirrorContainer.css('height', height + 'px');
            }
        }
        // we're dragging from right to left
        else {
            // clear all flags and selections from the left
            hasMultiple = false;
            selectedItems.removeClass('selectedItem');
            selectedItems = $([]);
        }
    })
        .on('over', function (el, container, source) {

            // hovering over right?
            var isOverRight = $(container).attr('id') === 'right';

            // hide the selections on the left
            //if (isOverRight) { // uncomment to show drop spots on left for multiples
            selectedItems.css('display','none');
            //}
        })
            .on('drop', function (el, target, source, sibling) {
                // convert to jquery
                target = $(target);

                // flag if dropped on right
                var isRight = target.attr('id') === 'right';

                // are we dropping multiple items?
                if (hasMultiple) {
                    // are we adding items to the right?
                    if(isRight) {
                        // get the default, single dropped item
                        var droppedItem = target.find('.selectedItem').first();

                        // replace it with the content of the mirror container
                        mirrorContainer.children().insertAfter(droppedItem);

                        // remove all vestigial items from the dom
                        $('.selectedItem').remove();

                        // clear flag
                        hasMultiple = false;
                    }
                    // we're keeping items on the left
                    else {
                        // retains original order in the left
                        drake.cancel(true);
                    }
                }
                // single selection case
                else {
                    // edge case: if only one item happened to be selected, remove the selected item class
                    right.children().removeClass('selectedItem');
                }
            })
            .on('cancel', function (el, container, source) {
                // nothing happening here
            })
            .on('out', function (el, container) {
                // unhide all
                selectedItems.css('display', '');
            })
            .on('moves', function (el, container, handle) {
                // i'm going to have non-draggable line breaks in my containers
                return !$(el).is('hr');
            })
            .on('dragend', function () {
                // rebind click event handlers for the new layouts
                unbindMultiselectOnTarget();
                bindMultiselectOnSource();

                // remove state classes for multiple selections that may be back on the left
                selectedItems.removeClass("gu-transit");
                selectedItems.css('display', '');
            });

        //#######################################
        // HELPER FUNCTIONS
        //#######################################

        // sets a global flag of whether the shift key is pressed
        function bindShiftPressEvent () {
            // set flag on
            $(document).keydown(function(event){
                if(event.shiftKey)
                    shiftIsPressed = true;
            });

            // set flag off
            $(document).keyup(function(){
                shiftIsPressed = false;
            });
        }

        // enables items on left to be multiselect with a "shift + click"
        function bindMultiselectOnSource () {
            cont.children().each((index, el) => {
                $(el).off('click');
            $(el).on('click', function () {
                if (shiftIsPressed)
                    $(this).toggleClass('selectedItem');
            });
        });
        };

        // disables multiselect on items on the right
        function unbindMultiselectOnTarget () {
            cont.children().each((index, el) => {
                $(el).off('click');
        });
        }

        // initial bindings
        function init() {
            bindShiftPressEvent();
            bindMultiselectOnSource();
        }

        // start this thing
        init();
    });


</script>

<style>

    body {
        background-color: #942A57;
        margin: 0 auto;
        font-family: Georgia, Helvetica;
        font-size: 17px;
        color: #ecf0f1;
        max-width: 760px;
    }

    html, body {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    *, *:before, *:after {
        -webkit-box-sizing: inherit;
        -moz-box-sizing: inherit;
        box-sizing: inherit;
    }

    label {
        display: block;
        font-size: 12px;
        font-style: italic;
        margin: 5px 0;
    }

    .parent {
        background-color: rgba(255, 255, 255, 0.2);
        margin: 50px 0;
        padding: 20px;
    }

    /* dragula-specific example page styles */
    .wrapper {
        display: table;
        width: 100%;
    }
    .container {
        display: table-cell;
        background-color: rgba(255, 255, 255, 0.2);
        width: 50%;
    }
    .container:nth-child(odd) {
        background-color: rgba(0, 0, 0, 0.2);
    }

    #right.container .selectedItem {
        display: block !important;
    }
    /*
     * note that styling gu-mirror directly is a bad practice because it's too generic.
     * you're better off giving the draggable elements a unique class and styling that directly!
     */
    .container > div,
    .gu-mirror, /* single selection */
    .gu-mirror > div /* multiple selection */{
        margin: 10px;
        padding: 10px;
        background-color: rgba(0, 0, 0, 0.2);
        transition: opacity 0.4s ease-in-out;
    }
    .container > div {
        cursor: move;
        cursor: grab;
        cursor: -moz-grab;
        cursor: -webkit-grab;
    }
    .gu-mirror {
        cursor: grabbing;
        cursor: -moz-grabbing;
        cursor: -webkit-grabbing;
        transform: rotate(-7deg);
    }
    .container > div.selectedItem {
        background-color: lightseagreen;
    }
    .container  div.selectedItem.gu-transit {
        background-color: rgba(0, 0, 0, 0.2);
    }

</style>



