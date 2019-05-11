<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box" style="padding: 30px 0;">
            <div class="btn-group pull-left">
                <ol class="breadcrumb hide-phone p-0 m-0">
                    <li class="breadcrumb-item"><a href="/">首页</a></li>
                    @if(!empty($location))
                        <?php $count = count($location); ?>
                        @foreach($location as $k => $item)
                            @if($k == $count-1)
                                <li class="breadcrumb-item active">{{ $item['title'] }}</li>
                            @else
                                <li class="breadcrumb-item"><a href="{{ $item['uri'] ? route($item['uri']) : 'javascript:;' }}">{{ $item['title'] }}</a></li>
                            @endif
                        @endforeach
                    @endif
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title end breadcrumb -->