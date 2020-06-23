@extends('layouts.master')

@section('content')

    <div id="menu-user">
        <ul>
            <li>Profile</li>
            <li><a href="http:\\adminsite.local">Payment</a></li>
            <li><a href="{{route('logout')}}">Logout</a></li>
        </ul>
    </div>
    <div id="user-profile">
            @if(Session::has('failed'))
            <div class="alert alert-danger"> {{ Session::get('failed') }}</div>
            @endif
            <form method="POST" action="{{route('update-user',$user->id)}}" onsubmit="return checkValidate()" autocomplete="off">
            @csrf
            {{ method_field('PUT') }}
            <table>
                <tr>
                    <td>Email</td>
                    <td><input type="text" name="email" disabled value="{{$user->email}}"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><p></p></td>
                </tr>
                <tr>
                    <td>Password&nbsp;&nbsp;&nbsp;</td>
                    <td><input type="password" name="editPassword" placeholder="Enter your password"></td>
                </tr>
               <tr>
                   <td></td>
                   <td><p class="error" id="edit-password-error"></p></td>
               </tr>
                <tr>
                    <td>User name</td>
                    <td><input type="text" name="editName" value="{{$user->name}}"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><p class="error" id="edit-name-error"></p></td>
                </tr>
                <tr>
                    <td>Sex</td>
                    <td>Male<input type="radio" value="M" name="gender" <?php echo ($user->gender == 'M') ? 'checked' : '' ?> >Female<input name="gender" value="FM" <?php echo ($user->gender == 'FM') ? 'checked' : '' ?> type="radio"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><p></p></td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td><input type="text" name="address" value="{{$user->address}}" ></td>
                </tr>
                <tr>
                    <td></td>
                    <td><p></p></td>
                </tr>
                <tr>
                    <td>Date of birth</td>
                    <td><input type="text" id="userDOB" name="date_of_birth" value="{{$user->date_of_birth}}" name="date_of_birth"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><p></p></td>
                </tr>
                <tr>
                    <td><button type="submit" id="btnSave">Save</button></td>
                </tr>
                
            </table>
        </form>
    </div>

<script src="{{asset('js/user-page.js')}}"></script>

@endsection