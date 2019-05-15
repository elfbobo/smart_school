@extends('admin.layout.form')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <select name="app_list[]" id="my-select" multiple>
                    @foreach($apps as $app)
                        <option value="{{ $app->id }}">{{ $app->name }}</option>
                    @endforeach
                </select>
            </div>
        </div> <!-- end col -->
    </div>
@endsection
@section('before-js')

@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            //多选框
            $("#my-select").bootstrapDualListbox({
                nonSelectedListLabel: false,
                selectedListLabel: false,
                filterTextClear: '全部',
                filterPlaceHolder: '过滤条件',
                preserveSelectionOnMove: 'moved',
                moveOnSelect: true,
                moveAllLabel: '添加全部',
                moveSelectedLabel: '添加选中',
                removeAllLabel: '移除全部',
                removeSelectedLabel: '移除选中',
                infoText: '选中/未选中共 {0} 项',
                infoTextFiltered: '从 {1} 项 筛选 {0} 项',
                infoTextEmpty: '空',
            });
        });

        if (parent.selectApp) {
            for (i=0;i<parent.selectApp.length;i++) {
                $('select option').each(function () {
                    if ($(this).val() == parent.selectApp[i]['app_id']) {
                        console.log($(this).val());
                        $(this).attr('selected', true);
                    }
                });
            }
        }
    </script>
@endsection