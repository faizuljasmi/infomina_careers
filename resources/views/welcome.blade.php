@extends('layouts.app', [
'class' => 'login-page',
'elementActive' => '',
'isPublic' => 'Yes'
])

@section('content')
@if(session()->has('message'))
<script>
    var hasMessage = true;
</script>
@endif
<div class="content col-md-12 ml-auto mr-auto">
    <div class="header py-5 pb-7 pt-lg-9">
        <div class="container col-md-10">
            <div class="header-body text-center mb-7">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-12 pt-5">
                        {{-- <h1 class="@if(Auth::guest()) text-white @endif">{{ __('Infomina Careers') }}</h1> --}}

                        <div class="output" id="output">
                            <h1 class="cursor"></h1>
                            <p></p>
                        </div>

                        <div class="content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Vacancies</h4>
                                        </div>
                                        @if(session()->has('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="icon fa fa-exclamation-triangle"></i>
                                            {!! session('error') !!}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        @endif
                                        <div class="card-body">
                                            <div class="table" style="overflow-x:auto;">
                                                <table class="table-striped col-md-12">
                                                    <thead class=" text-primary">
                                                        <th>Job Title</th>
                                                        <th>Location</th>
                                                        <th>Type</th>
                                                        <th>Published</th>
                                                        <th>Action</th>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($vacancies as $vc)
                                                        <tr>
                                                            <td>{{$vc->job_title}}</td>
                                                            <td>{{$vc->location}}</td>
                                                            <td>{{$vc->job_type}}</td>
                                                            <td>{{$vc->created_at->diffForHumans()}}</td>
                                                            <td><a href="{{ route('view-vacancy', $vc) }}"><button
                                                                        type="button"
                                                                        class="btn btn-primary">View</button></a></td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                {{ $vacancies->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Application Succesful Modal -->
<div class="modal fade" id="successModalCenter" tabindex="-1" role="dialog" aria-labelledby="successModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="successModalLongTitle">Application Submitted Succesfully</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                {!! strip_tags(session()->get('message')) !!}
                <h5> {!! strip_tags(session()->get('apl_num')) !!}</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Check ApplicationModal -->
<div class="modal fade" id="applicationModalCenter" tabindex="-1" role="dialog"
    aria-labelledby="applicationModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog  modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="applicationModalLongTitle">Check Application Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ">
                <form method="GET" action="{{route('view-application')}}" id="view-application">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="apl_no">Application Number</label>
                            <input type="text"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                class="form-control" id="apl_no" name="apl_no" placeholder="Application Number"
                                required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success float-center">Check</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
    form,
    label,
    p {
        color: black !important;
        font-weight: bold;
    }

    .form-control:focus {
        border-color: rgba(49, 162, 236, 0.548);
        border-width: 1.5px !important;
    }

    .form-control {
        border-color: rgba(88, 88, 88, 0.459);
        border-width: 1px !important;
    }

    body {
        background-color: #4e54c8;
    }

    .output {
        text-align: center;
        font-family: 'Source Code Pro', monospace;
        color: white;
        font-size: 10px;

        h1 {
            font-size: 10px;
        }
    }

    /* Cursor Styling */

    .cursor::after {
        content: '';
        display: inline-block;
        margin-left: 3px;
        background-color: white;
        animation-name: blink;
        animation-duration: 0.5s;
        animation-iteration-count: infinite;
    }

    h1.cursor::after {
        height: 24px;
        width: 13px;
    }

    p.cursor::after {
        height: 13px;
        width: 6px;
    }

    @keyframes blink {
        0% {
            opacity: 1;
        }

        49% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }

        100% {
            opacity: 0;
        }
    }
</style>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
            demo.checkFullPageBackgroundImage();
            if(hasMessage == true){
                $("#successModalCenter").modal({ show : true });
            }
        });

        // values to keep track of the number of letters typed, which quote to use. etc. Don't change these values.
var i = 0,
    a = 0,
    isBackspacing = false,
    isParagraph = false;

// Typerwrite text content. Use a pipe to indicate the start of the second line "|".
var textArray = [
  "Discover your career with us.",
  "Discover opportunities with us.",
  "Discover your future with us.",
];

// Speed (in milliseconds) of typing.
var speedForward = 100, //Typing Speed
    speedWait = 1000, // Wait between typing and backspacing
    speedBetweenLines = 1000, //Wait between first and second lines
    speedBackspace = 25; //Backspace Speed

//Run the loop
typeWriter("output", textArray);

function typeWriter(id, ar) {
  var element = $("#" + id),
      aString = ar[a],
      eHeader = element.children("h1"), //Header element
      eParagraph = element.children("p"); //Subheader element

  // Determine if animation should be typing or backspacing
  if (!isBackspacing) {

    // If full string hasn't yet been typed out, continue typing
    if (i < aString.length) {

      // If character about to be typed is a pipe, switch to second line and continue.
      if (aString.charAt(i) == "|") {
        isParagraph = true;
        eHeader.removeClass("cursor");
        eParagraph.addClass("cursor");
        i++;
        setTimeout(function(){ typeWriter(id, ar); }, speedBetweenLines);

      // If character isn't a pipe, continue typing.
      } else {
        // Type header or subheader depending on whether pipe has been detected
        if (!isParagraph) {
          eHeader.text(eHeader.text() + aString.charAt(i));
        } else {
          eParagraph.text(eParagraph.text() + aString.charAt(i));
        }
        i++;
        setTimeout(function(){ typeWriter(id, ar); }, speedForward);
      }

    // If full string has been typed, switch to backspace mode.
    } else if (i == aString.length) {

      isBackspacing = true;
      setTimeout(function(){ typeWriter(id, ar); }, speedWait);

    }

  // If backspacing is enabled
  } else {

    // If either the header or the paragraph still has text, continue backspacing
    if (eHeader.text().length > 0 || eParagraph.text().length > 0) {

      // If paragraph still has text, continue erasing, otherwise switch to the header.
      if (eParagraph.text().length > 0) {
        eParagraph.text(eParagraph.text().substring(0, eParagraph.text().length - 1));
      } else if (eHeader.text().length > 0) {
        eParagraph.removeClass("cursor");
        eHeader.addClass("cursor");
        eHeader.text(eHeader.text().substring(0, eHeader.text().length - 1));
      }
      setTimeout(function(){ typeWriter(id, ar); }, speedBackspace);

    // If neither head or paragraph still has text, switch to next quote in array and start typing.
    } else {

      isBackspacing = false;
      i = 0;
      isParagraph = false;
      a = (a + 1) % ar.length; //Moves to next position in array, always looping back to 0
      setTimeout(function(){ typeWriter(id, ar); }, 50);

    }
  }
}
</script>
@endpush
