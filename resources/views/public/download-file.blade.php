@extends('layouts.public')
@section('content')
    <div class="card">
        <div class="card-body">

            @if($isExpired)
                <div class="alert alert-danger">
                    Download Link has been expired.
                </div>
            @else
                <div class="text-right">
                <a target="_blank" href="{{ route('public.download-zip',$fileDownload->download_code) }}" class="btn btn-sm btn-primary">
                        <i class="fa fa-file-archive"></i>
                        Download All Files
                    </a>
                </div>

                <div class="mt-2">
                    <ul class="list-group">
                        @foreach($fileDownload->details as $detail)
                            <li class="list-group-item">
                                <a class="text-dark" target="_blank" href="{{ route('project.attachment',$detail->associate->file_key) }}">
                                    <i class="fas fa-cloud-download-alt"></i> {{ $detail->associate->file_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

            @endif



        </div>
    </div>

@stop
