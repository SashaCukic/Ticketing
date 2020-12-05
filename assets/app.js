/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// loads the packages from node_modules
const $ = require('jquery');
require('bootstrap');

// loads the files from assets
//var greet = require('./greet');

$(document).ready(function() {

    $('#search').on('change', function() {

        var data = {};
        data.id = this.value;

        $.ajax({
            type: 'POST',
            url: "/ajax/ticket/search",
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response != '') {
                    $("#tickets-table").html(response.html);
                }
            },
            error: function() {
                //handle error
            },
        });
    });


});