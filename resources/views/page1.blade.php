@extends('layouts.main')

@section('content')
    
    <div class="container">
        <h1>Bienvenue dans Manivelle !</h1>
        
        <form class="form-sign-up">
            
            <fieldset>
                <div class="input-group">
                    <label for="">Nom de votre organisation</label>
                    <input class="form-control" type="text" name="organisation" value="">
                </div>    
            </fieldset>
            
            <fieldset>
                <div class="input-group">
                    <label for="">Votre nom</label>
                    <input class="form-control" type="text" name="name" value="">
                </div>
                
                <div class="input-group">
                    <label for="">Courriel</label>
                    <input class="form-control" type="email" name="email">
                </div>
            </fieldset>
            
            <fieldset>
                <div class="input-group">
                    <label for="">Mot de passe</label>
                    <input class="form-control" type="password" name="password">
                </div>
                
                <div class="input-group">
                    <label for="">Comfirmation du mot de passe</label>
                    <input class="form-control" type="password" name="password">
                </div>
            </fieldset>
                
            <button class="btn btn-default" type="submit">Commencer</button>
            
        </form>

    </div>
    
@endsection
