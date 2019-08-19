@extends('layouts.page')
@section('content')

    <!-- message template -->
    <div class="success callout" id="messageTemplate" style="display:none;" data-closable="">
        <span data-message=""></span>
        <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <!-- message template -->

    <div class="row">
        <div class="columns small-3">

            <!--
            <label>System Online :</label>
            <div class="switch">
              <input class="switch-input" id="system-online" type="checkbox" name="systemStatus" checked>
              <label class="switch-paddle" for="system-online">
                <span class="show-for-sr">System Status</span>
                <span class="switch-active" aria-hidden="true">Yes</span>
                <span class="switch-inactive" aria-hidden="true">No</span>
              </label>
            </div>
            -->

            <button class="secondary button " id="expireServiceDesks">Expire Service Desks</button>
        </div>

        <div class="columns small-9" id="dash-messages-container">

        </div>
    </div>

    <div class="reveal" id="modal" data-reveal>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>

        <div>
            adds
        </div>

    </div>

    <div class="reveal" id="add-attendant-modal" data-reveal>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <div>

            <h2>Attendant</h2>

            <form method=post action="admin/attendant">
                <div class="grid-container">
                    <div class="grid-x grid-padding-x">

                        <input type="hidden" name="att_id">

                        <div class="medium-12 cell">
                            <label>First Name
                                <input type="text" required name="att_first_name" placeholder="First Name">
                            </label>
                        </div>

                        <div class="medium-12 cell">
                            <label>Second Name
                                <input type="text" required name="att_second_name" placeholder="Second Name">
                            </label>
                        </div>

                        <div class="medium-12 cell">
                            <label>Email
                                <input type="email" required name="att_email" placeholder="Email">
                            </label>
                        </div>

                        <fieldset class="large-5 cell">
                            <legend>Active</legend>
                            <input type="radio" required name="att_active" value="Y" id="pokemonRed" required><label for="pokemonRed">Yes</label>
                            <input type="radio" name="att_active" value="N" id="pokemonBlue"><label for="pokemonBlue">No</label>
                        </fieldset>

                        <input type="submit" class="button" value="Save" />

                    </div>
                </div>
            </form>


        </div>
    </div>

    <div class="reveal" id="add-service-desk-modal" data-reveal>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <div>

            <h2>Service Desk</h2>

            <form method="post" action="admin/servicedesk">
                <div class="grid-container">
                    <div class="grid-x grid-padding-x">

                        <input type="hidden" name="src_id">

                        <div class="medium-12 cell">
                            <label>Name
                                <input type="text" required name="src_centre_name" placeholder="First Name">
                            </label>
                        </div>

                        <div class="medium-12 cell">
                            <label>Description
                                <textarea name="src_centre_desc" ></textarea>
                            </label>
                        </div>

                        <fieldset class="large-5 cell">
                            <legend>Active</legend>
                            <input type="radio" required name="src_active" value="Y" id="pokemonRed" required><label for="pokemonRed">Yes</label>
                            <input type="radio" required name="src_active" value="N" id="pokemonBlue"><label for="pokemonBlue">No</label>
                        </fieldset>

                        <input type="submit" class="button" value="Save" />

                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="reveal" id="add-rolling-message-modal" data-reveal>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <div>

            <h2>Rolling Message</h2>

            <form method="post" action="admin/rollingmessage">
                <div class="grid-container">
                    <div class="grid-x grid-padding-x">

                        <input type="hidden" name="rmg_id">

                        <div class="medium-12 cell">
                            <label>Message
                                <textarea required name="rmg_message" ></textarea>
                            </label>
                        </div>

                        <fieldset class="large-12 cell">
                            <legend>Queue</legend>
                            <input type="radio" required name="rmg_que_id" value="1" id="pokemonRed" required><label for="pokemonRed">16-18</label>
                            <input type="radio" required name="rmg_que_id" value="2" id="pokemonBlue"><label for="pokemonBlue">19 Plus</label>
                            <input type="radio" required name="rmg_que_id" value="3" id="pokemonBlue"><label for="pokemonBlue">Missed Appointments</label>
                            <input type="radio" required name="rmg_que_id" value="0" id="pokemonBlue"><label for="pokemonBlue">No Shows</label>
                        </fieldset>

                        <fieldset class="large-12 cell">
                            <legend>Active</legend>
                            <input type="radio" required name="rmg_active" value="Y" id="pokemonRed" required><label for="pokemonRed">Yes</label>
                            <input type="radio" required name="rmg_active" value="N" id="pokemonBlue"><label for="pokemonBlue">No</label>
                        </fieldset>

                        <input type="submit" class="button" value="Save" />

                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="row text-center" >
        <div>
            <button class="primary button" data-add-attendant >Add Attendant</button>
            <button class="primary button" data-add-service-desk >Add Service Desk</button>
            <button class="primary button" data-add-rolling-message >Add Rolling Message</button>
        </div>
    </div>

    <div class="row" >

        @if ($message = Session::get('success'))
            <div class="success callout" data-closable>
                <p>{{ $message }}</p>
                <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <ul class="tabs" data-tabs id="admin-tabs">
            <li class="tabs-title is-active"><a href="#panel1" aria-selected="true">Attendants</a></li>
            <li class="tabs-title"><a href="#panel2">Service Desks</a></li>
            <li class="tabs-title"><a href="#panel3">Rolling Messages</a></li>
        </ul>

            <fieldset class="fieldset">
                <legend>Options</legend>
                <input  type="checkbox" checked name="showInActive" /><label for="showInActive">Hide In Active</label>
            </fieldset>

        <div class="tabs-content" data-tabs-content="admin-tabs">


                <div class="tabs-panel is-active" id="panel1" data-view="vw_attendants">
                </div>

                <div class="tabs-panel" id="panel2" data-view="vw_service_desk" >
                </div>

                <div class="tabs-panel" id="panel3" data-view="vw_messages" >
                </div>

          </div>

    </div>

    <script>

        $( document ).on( "click", "[data-att-id]", function() {

            $.getJSON("/admin/attendant/" + $(this).attr('data-att-id') , function(result){
                $('#add-attendant-modal').foundation('open');

                $('[name=att_id]').val( result.att_id );
                $('[name=att_first_name]').val( result.att_first_name );
                $('[name=att_second_name]').val( result.att_second_name );
                $('[name=att_email]').val( result.att_email );
                $('[name=att_active][value='+ result.att_active +']').prop('checked',true);

            });

        });

        $( document ).on( "click", "[data-src-id]", function() {


            $.getJSON("/admin/servicedesk/" + $(this).attr('data-src-id') , function(result){
                $('#add-service-desk-modal').foundation('open');

                $('[name=src_id]').val( result.src_id );
                $('[name=src_centre_name]').val( result.src_centre_name );
                $('[name=src_centre_desc]').val( result.src_centre_desc );
                $('[name=src_active][value='+ result.src_active +']').prop('checked',true);

            });

        });

        $( document ).on( "click", "[data-rmg-id]", function() {


            $.getJSON("/admin/rollingmessage/" + $(this).attr('data-rmg-id') , function(result){
                $('#add-rolling-message-modal').foundation('open');

                $('[name=rmg_id]').val( result.rmg_id );
                $('[name=rmg_que_id][value='+ result.rmg_que_id +']').prop('checked',true);
                $('[name=rmg_message]').val( result.rmg_message );
                $('[name=rmg_active][value='+ result.rmg_active +']').prop('checked',true);

            });

        });

        $( document ).on( "click", "[data-rmg-id-delete]", function() {

            window.location.href = 'admin/rollingmessage/delete/' + $(this).attr('data-rmg-id-delete');
        });

        $( document ).on( "click", "[data-add-attendant]", function() {
           $('#add-attendant-modal').foundation('open');

            $('[name=att_id]').val( '' );
            $('[name=att_first_name]').val( '' );
            $('[name=att_second_name]').val( '' );
            $('[name=att_email]').val( '' );
            $('[name=att_active][value=Y]').prop('checked',true);

        });

        $( document ).on( "click", "[data-add-service-desk]", function() {
            $('#add-service-desk-modal').foundation('open');

            $('[name=src_id]').val( '' );
            $('[name=src_centre_name]').val( '' );
            $('[name=src_centre_desc]').val( '' );
            $('[name=src_active][value=Y]').prop('checked',true);
        });

        $( document ).on( "click", "[data-add-rolling-message]", function() {
            $('#add-rolling-message-modal').foundation('open');

            $('[name=rmg_id]').val( '' );
            $('[name=rmg_que_id][value=1]').prop('checked',true);
            $('[name=rmg_message]').val( '' );
            $('[name=rmg_active][value=Y]').prop('checked',true);
        });



    </script>

@stop