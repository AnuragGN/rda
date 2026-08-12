<!-- Candid Search Modal -->
<div class="modal fade" id="candidSearchModal" tabindex="-1" role="dialog" aria-labelledby="candidSearchModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form class="form" role="form" autocomplete="off" id="candidSearchForm" method="POST" action="/">

                {{ csrf_field() }}

                <div class="modal-header">
                    <h4 class="modal-title">Find organization on GuideStar (Candid)</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body gn-form">
                    <span id="ide_candid_search" class="form-error" style="display: none">Your request could not be completed</span>

                    <div class="form-group row">
                        {!! Form::label('query', 'Enter organization name, EIN or keywords', ['class' => 'col-sm-12 col-form-label']) !!}
                        <div class="col-sm-12">
                            {!! Form::text('query', "", ['class' => 'form-control', 'id' => 'id_candid_search_text']) !!}
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <a class="btn btn-theme" id="id_candid_find_now" href="javascript:void(0);" onclick="grantForm.onCandidSearch();">Find Now</a>
                    <img src="/images/spinner.gif" id="id_candid_find_in_progress" style="display: none;">
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Candid Response Modal -->
<div class="modal fade" id="candidResponseModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="candidResponseModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">GUIDESTAR (CANDID) RESULTS</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div id="id_candid_search_results"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-theme btn-sm pl-4 pr-4" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function onShowCandidSearchModal() {
        var value = $('#id_org_name_typeahead').val();
        $('#id_candid_search_text').val(value);
        $('#candidSearchModal').modal('show');
    }
</script>
