@extends('layouts.main')

@section('content')
    
    <div class="container">
        <div class="container-screens">
            <h1>Écrans</h1>
            
            <div class="btn-group btn-group-screen btn-group-screen-added">
                
                <button class="btn btn-default btn-default-screen" type="button" name="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                    <div class="btn-banner-offline">
                        HORS LIGNE
                    </div>
                    <div class="glyphicon glyphicon-ok-sign btn-icon-screen"></div>
                    <div class="btn-labels-screen">
                        <div>
                            Hall d’entrée
                        </div>
                        <div class="btn-sublabel-screen">
                            Écran vertical interactif 42”
                        </div>
                    </div>    
                </button>
                
                <div class="dropdown-menu dropdown-menu-add dropdown-menu-screen">
        
                    <form class="dropdown-menu-screen-form" action="" method="">
                        <div class="input-group">
                            <label for="">Nom de l’écran</label>
                            <input class="form-control dropdown-menu-screen-form-left input-dropdown-menu-screen" type="text" name="email" value="">
                            <button class="btn btn-default dropdown-menu-screen-form-right" type="submit">Configurer</button>
                        </div>
                
                        <div class="input-group">
                            <label class="dropdown-menu-screen-form-right" for="">Réutiliser la conﬁguration d’un autre écran :</label>
                            <input class="dropdown-menu-screen-form-left" type="checkbox" name="vehicle" value="Bike">
                        </div>
                        
                        <div class="input-group input-group-screen-form">
                            <label class="sr-only" for="">Role</label>
                            <select class="form-control" name="role">
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">5</option>
                            </select>
                        </div>        
                    </form>
                        
                </div>
            </div>
            
            <div class="btn-group btn-group-screen btn-group-screen-added btn-group-screen-offline">
                
                <button class="btn btn-default btn-default-screen" type="button" name="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                    <div class="btn-banner-offline">
                        HORS LIGNE
                    </div>
                    <div class="glyphicon glyphicon-exclamation-sign btn-icon-screen"></div>
                    <div class="btn-labels-screen">
                        <div>
                            Hall d’entrée
                        </div>
                        <div class="btn-sublabel-screen">
                            Écran vertical interactif 42”
                        </div>
                    </div>    
                </button>
                
                <div class="dropdown-menu dropdown-menu-add dropdown-menu-screen">
        
                    <form class="dropdown-menu-screen-form" action="" method="">
                        <div class="input-group">
                            <label for="">Nom de l’écran</label>
                            <input class="form-control dropdown-menu-screen-form-left input-dropdown-menu-screen" type="text" name="email" value="">
                            <button class="btn btn-default dropdown-menu-screen-form-right" type="submit">Configurer</button>
                        </div>
                
                        <div class="input-group">
                            <label class="dropdown-menu-screen-form-right" for="">Réutiliser la conﬁguration d’un autre écran :</label>
                            <input class="dropdown-menu-screen-form-left" type="checkbox" name="vehicle" value="Bike">
                        </div>
                        <div class="input-group input-group-screen-form">
                            <label class="sr-only" for="">Role</label>
                            <select class="form-control" name="role">
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">5</option>
                            </select>
                        </div>
                
                        
                    </form>

                        
                </div>
            </div>
                    
            <div class="btn-group btn-group-screen btn-group-screen-added">
                
                <button class="btn btn-default btn-default-screen" type="button" name="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                    <div class="glyphicon glyphicon-plus btn-icon-screen"></div>
                    <div class="btn-labels-screen">
                        Ajouter un écran
                    </div>    
                </button>
                
                <div class="dropdown-menu dropdown-menu-add dropdown-menu-screen">
                    
                    <p>Assurez-vous que l’écran soit allumé et connecté à internet.</p>
                    
                    <form class="dropdown-menu-screen-form" action="" method="">
                        
                        <label for="">Code d’authentification</label>
                        <input class="form-control dropdown-menu-screen-form-left input-dropdown-menu-screen" type="text" name="email" value="">
                        <button class="btn btn-default dropdown-menu-screen-form-right" type="submit">Continuer</button>
                        
                    </form>
                    
                    <p>Ce code de 4 chiffres apparaît à l’écran lors de sa première mise en marche.</p>
                        
                </div>
            </div>
            
            
        </div>
        
        <div class="container-team">
            
            <h2>Équipe</h2>
            
            <div class="profile-team">
                
                <div class="profile-team-avatar"></div>
                <div class="profile-team-info">
                    <span>Marco Babin</span>
                    <span>Administration</span>
                </div>
                
            </div>
            
            <div class="btn-group btn-group-team">
                
                <button class="btn btn-default btn-team" type="button" name="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                    <span class=" btn-team-plus glyphicon glyphicon-plus "></span>
                    <span class="btn-team-label">
                        Ajouter un écran
                    </span>    
                </button>
                
                <form class="dropdown-menu dropdown-menu-add">
                    
                    <div class="input-group">
                        <label for="">Couriel</label>
                        <input class="form-control" type="email" name="email" value="">
                    </div>    
                    
                    <div class="input-group">
                        <label for="">Role</label>
                        <select class="form-control" name="role">
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">5</option>
                        </select>
                    </div> 
                           
                    <p>Gestion de contenu permet de contrôler ce qui estaffiché sur les écrans.</p>    
                    <p>Administration permet également d’ajouter et de retirerdes membres de l’équipe.</p>            
                    <button class="btn btn-default" type="submit">Inviter</button>
                    
                </form>
            </div>
            
        </div>    
    </div>
    
@endsection
