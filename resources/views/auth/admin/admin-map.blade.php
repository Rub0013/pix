@extends('layouts.admin')

@section('title')
    Admin-map
@endsection

@section('content')
    <div class="main-map flex">
        <div class="add-branch">
            <p>Добавить филиал</p>
            <div class="branches-validation-errors"></div>
            <div class="form-group">
                <label for="branch-latitude">Широта</label>
                <input class="form-control" id="branch-latitude" type="text" placeholder="Введите широту.">
            </div>
            <div class="form-group">
                <label for="branch-longitude">Долгота</label>
                <input class="form-control" id="branch-longitude" type="text" placeholder="Введите долготу.">
            </div>
            <div class="form-group">
                <label for="branch-title">Название</label>
                <input class="form-control" id="branch-title" type="text" placeholder="Введите название филиала.">
            </div>
            <div class="form-group">
                <label for="branch-address">Адрес</label>
                <textarea class="form-control" id="branch-address" type="text" placeholder="Введите адрес филиала."></textarea>
            </div>
            <div class="form-group add-product-btn-block flex">
                <button id="add-branch-btn" class="btn btn-primary">Добавить</button>
            </div>
        </div>
        <div class="added-branches">
            <p>Все филиалы</p>
            @if(count($branches) > 0)
                <div class="panel-group scrollbar" id="added-branches-panel" role="tablist" aria-multiselectable="true">
                    @foreach($branches as $branch)
                        <div id="branch_{{$branch->id}}" class="panel one-branch">
                            <div class="branch-info">
                                <div class="branch-title-block">
                                    <p>Название</p>
                                    <p class="branch-title">{{$branch->title}}</p>
                                </div>
                                <div class="branch-address-block">
                                    <p>Адрес филиала</p>
                                    <p class="branch-address">{{$branch->address}}</p>
                                </div>
                            </div>
                            <div class="branch-buttons flex">
                                <button class="btn btn-info btn-sm upt-branch">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </button>
                                <input type="hidden" value="{{$branch->id}}">
                                <button class="btn btn-danger btn-sm delete-branch">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <div class="modal fade" id="branch-update-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Изменить</h4>
                </div>
                <div class="modal-body">
                    <div class="branches-upd-validation-errors"></div>
                    <div class="form-group">
                        <label for="branch-title">Название</label>
                        <input class="form-control" id="upt-branch-title" type="text" placeholder="Введите название филиала.">
                    </div>
                    <div class="form-group">
                        <label for="branch-address">Адрес</label>
                        <textarea class="form-control" id="upt-branch-address" type="text" placeholder="Введите адрес филиала."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <input type="hidden" class="form-control" id="branchIdModal">
                    <button type="button" class="btn btn-primary" id="branch-upd-modal-btn">Обновить</button>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('/js/admin/map.js') }}"></script>
@endsection
