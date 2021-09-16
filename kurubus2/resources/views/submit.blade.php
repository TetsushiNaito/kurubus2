@extends( 'layouts.app_base' )

@section( 'title', 'Submit')

@section( 'content' )
    <div id="input_area">
        <form action="/confirm" method="POST">
        <table class="table table-borderless">
        @csrf
            <tr>
                <td colspan="2" class="text-center">
                    <!-- 出発地バス停名の入力 -->
                        <label for="depr_pole">出発地バス停：</label>
                        <input type="text" name="depr_pole" value="{{old('depr_pole')}}"><span id="result1"></span>
                        @if ( $errors->has('depr_pole'))
                        {{$errors->first('depr_pole')}}
                        @endif
                </td> 
            </tr>
            <tr>
                <td colspan="2" class="text-center">
                        <!-- 目的地バス停名の入力 -->
                        <label for="dest_pole">目的地バス停：</label>
                        <input type="text" name="dest_pole" value="{{old('dest_pole')}}"><span id="result2"></span>
                        @if ( $errors->has('dest_pole'))
                        {{$errors->first('dest_pole')}}
                        @endif
                </td>
            </tr>
            <tr>
                <td style="width:50%; text-align:right">
                    <button type="submit" class="btn btn-primary">登録</button>
                </td>
                <td style="width:50%; text-align:left">
                    <button type="button" class="btn btn-primary" onclick="document.location='http://localhost/timetable';">取消</button>
                </td>
            </tr>
        </table>
    </form>
    </div>
@endsection