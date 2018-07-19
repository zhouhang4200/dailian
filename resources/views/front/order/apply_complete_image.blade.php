<div carousel-item="" id="">
    @foreach($image as $key => $item)
    <div  style="background: url({{ $item->path }}) no-repeat center/contain;"  @if($key == 0) class="layui-this" @endif>
    </div>
    @endforeach
</div>
