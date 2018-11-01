function initCommentBoxIo() {

    jQuery(document).ready(function() {

        if (!commentBoxIoOptions) {
            commentBoxIoOptions = {};
        }

        commentBox(commentBoxIoOptions['project-id'], {
            className: commentBoxIoOptions['class-name'], // the class of divs to look for
            defaultBoxId: commentBoxIoOptions['box-id'], // the default ID to associate to the div
            tlcParam: commentBoxIoOptions['comment-link-param'], // used for identifying links to comments on your page
            backgroundColor: commentBoxIoOptions['background-color'], // default transparent
            textColor: commentBoxIoOptions['text-color'], // default black
            subtextColor: commentBoxIoOptions['subtext-color'], // default grey
            /**
             * Creates a unique URL to each box on your page.
             *
             * @param {string} boxId
             * @param {Location} pageLocation - a copy of the current window.location
             * @returns {string}
             */
            createBoxUrl(boxId, pageLocation) {

                pageLocation.href = commentBoxIoOptions['permalink'];
                //pageLocation.search = ''; // removes query string!
                pageLocation.hash = boxId; // creates link to this specific Comment Box on your page
                return pageLocation.href; // return url string
            },
            /**
             * Fires once the plugin loads its comments.
             * May fire multiple times in its lifetime.
             *
             * @param {number} count
             */
            onCommentCount(count) {

                if (commentBoxIoOptions['comment-count-selector']) {
                    jQuery(commentBoxIoOptions['comment-count-selector']).text(count);
                }
            }
        });
    });
}

initCommentBoxIo();