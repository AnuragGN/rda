
<table>
    <tr>
        <th>EIN No</th>
        <th>Organization Name</th>
        <th>Address</th>
        <th>Phone No</th>
        {{--<th></th>--}}
    </tr>

    @foreach($orgs as $i => $org)
        <tr title="Click to select this organization" onclick="onSelect({{$i}})">
            <td>{{$org['ein']}}</td>
            <td>{{$org['name']}}</td>
            <td>{{$org['address']}}</td>
            <td>{{$org['contact_phone']}}</td>
            {{--<td>{{ 'Select' }}</td>--}}
        </tr>
    @endforeach

</table>

<script>
    var orgs = <?php echo json_encode($orgs) ?>;
    function onSelect(index) {
        grantForm.onCandidOrgSelection(orgs[index]);
    }
</script>