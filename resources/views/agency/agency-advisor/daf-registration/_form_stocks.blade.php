
<form method="POST" action="{{ route('post-agency-daf-contributions-stocks', $id) }}"
      class="daf-contribution-form" id="daf-contribution-{{ $stock->key }}">
    @csrf
    <input type="hidden" name="key" id="id_stock_key" value="{{ $stock->key }}">
    <input type="hidden" name="isNew" value="{{ $stock->isNew }}">

<div class="form-group row">
    <label for="id_stock_name" class="col-md-3 col-form-label text-right pr-0">Name of Stock</label>
    <div class="col-md-6">
        <input type="text" name="stock_name" id="id_stock_name" class="form-control"
               onkeypress="return /^[A-Za-z\s]*$/i.test(event.key)"
               minlength="3" maxlength="32"
               value="{{ old('stock_name', $stock->stock_name ?? '') }}" required>
    </div>
</div>

<div class="form-group row">
    <label for="id_shares" class="col-md-3 col-form-label text-right pr-0">Number of Shares</label>
    <div class="col-md-6">
        <input type="text" name="shares" id="id_shares" class="form-control"
               onkeypress="return /^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/i.test(event.key)"
               maxlength="10"
               value="{{ old('shares', $stock->shares ?? '') }}" required>
    </div>
</div>

<div class="form-btn-bar">
    <div class="col-md-12 form-footer">
        <div class="row">
            <p class="offset-md-3 col-md-3">
                <button type="submit" name="save" id="id_save_btn" class="btn btn-wide btn-accent w100">SAVE</button>
            </p>
            <p class="col-md-3">
                <button type="submit" name="save_next" class="btn btn-accent w100">SAVE & NEXT</button>
            </p>
        </div>
    </div>
</div>

@if(!$stock->isNew)
    <div class="offset-md-9 col-md-3 pl-0 text-right mb-1">
        <a href="javascript:void(0);" class="delete-stock" id="id_delete_orgs-{{$stock->key}}" onclick="deleteStock(this)" data="{{$stock->key}}">Delete</a>
    </div>
@endif  

</form>
