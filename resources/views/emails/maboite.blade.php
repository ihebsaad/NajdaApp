
<table>
    <thead>
    <tr>
        <th>Num</th>
        <th>Sujet</th>
        <th>Emetteur</th>
        <th>Attachements</th>
    </tr>
    </thead>
    <tbody>
    @if($paginator->count() > 0)
        @foreach($paginator as $oMessage)
            <tr>
                <td>{{$oMessage->getUid()}}</td>
                <td><a href="{{action('EmailController@open', $oMessage->getUid())}}">{{$oMessage->getSubject()}}</a></td>
                <td>{{$oMessage->getFrom()[0]->mail}}</td>
                <td>{{$oMessage->getAttachments()->count() > 0 ? 'oui' : 'non'}}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="4">Pas de Messages</td>
        </tr>
    @endif
    </tbody>
</table>

{{$paginator->links()}}


