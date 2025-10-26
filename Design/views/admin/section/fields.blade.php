<?php 
$sknew = (isset($sk))?(int)$sk+1:'';
if(isset($firstrow) || config('design.section.'.$design['pointslug'].'.'.$k.'.static'))
{
    $sknew = '';
}
?>
@if(isset($edit))
<?php 
$scontent = json_decode($sv['content'],true);
?>
@foreach(config('design.section.'.$design['pointslug'].'.'.$k.'.fields') as $fieldk => $fieldv)
@if($fieldv['type']=='input')
<div class="form-group">
    <label for="title">{{$fieldv['label'] ?? ucfirst($fieldk)}}</label>
    <input type="text" class="form-control {{$fieldv['class'] ?? ''}}" id="{{$k}}{{$fieldk}}{{$sknew}}" placeholder="Enter {{$fieldv['label'] ?? $fieldk}}"
        name="{{$k}}{{$fieldk}}{{$sknew}}" value="{{$scontent[$fieldk] ?? ''}}" {{$fieldv['attr'] ?? ''}} />
</div>
@elseif($fieldv['type']=='editor')
<div class="form-group">
    <label for="content">{{$fieldv['label'] ?? ucfirst($fieldk)}}</label>
    <textarea class="form-control required tinyarea" id="{{$k}}{{$fieldk}}{{$sknew}}" name="{{$k}}{{$fieldk}}{{$sknew}}"
        placeholder="Enter Content" data-empty="Enter Content" data-allowed="{{ $fieldv['allowed'] ?? ''}}">{{$scontent[$fieldk]}}</textarea>
</div>
@elseif($fieldv['type']=='image')
<div class="form-group">
    @include('Gallery::admin.inc.mediainput',['name'=>$k.$fieldk.$sknew,'edit'=>true,'value'=>$scontent[$fieldk]
    ??
    '','class'=>$fieldv['class'] ?? '','attr'=>$fieldv['attr'] ?? '', 'label' => $fieldv['label'] ?? ucfirst($fieldk), 'showremove' => true, 'sizemessage' => $fieldv['size-message'] ?? ''])
</div>
@endif
@endforeach
@else
@foreach(config('design.section.'.$design['pointslug'].'.'.$k.'.fields') as $fieldk => $fieldv)
@if($fieldv['type']=='input')
<div class="form-group">
    <label for="title">{{$fieldv['label'] ?? ucfirst($fieldk)}}</label>
    <input type="text" class="form-control {{$fieldv['class'] ?? ''}}" id="{{$k}}{{$fieldk}}{{$sknew}}" placeholder="Enter {{$fieldv['label'] ?? $fieldk}}"
        name="{{$k}}{{$fieldk}}{{$sknew}}" {{$fieldv['attr'] ?? ''}} />
</div>
@elseif($fieldv['type']=='editor')
<div class="form-group">
    <label for="content">{{$fieldv['label'] ?? ucfirst($fieldk)}}</label>
    <textarea class="form-control required @if(config('design.section.'.$design['pointslug'].'.'.$k.'.static')) tinyarea @endif" id="{{$k}}{{$fieldk}}{{$sknew}}" name="{{$k}}{{$fieldk}}{{$sknew}}"
        placeholder="Enter Content" data-empty="Enter Content" data-allowed="{{ $fieldv['allowed'] ?? ''}}"></textarea>
</div>
@elseif($fieldv['type']=='image')
<div class="form-group">
    @include('Gallery::admin.inc.mediainput',['name'=>$k.$fieldk.$sknew,'class'=>$fieldv['class'] ?? '','attr'=>$fieldv['attr'] ?? '', 'label' => $fieldv['label'] ?? ucfirst($fieldk), 'showremove' => true, 'sizemessage' => $fieldv['size-message'] ?? ''])
</div>
@endif
@endforeach
@endif