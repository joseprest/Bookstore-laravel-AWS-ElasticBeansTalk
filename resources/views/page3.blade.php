@extends('layouts.main')

@section('content')
    
    <div class="container">
        <h1><span class="glyphicon glyphicon-phone"></span>Hall d’entrée</h1>
    </div>
    
    <div class="container-alerts container-alerts-off">
        
        <div class="container">
            <div class="btn-group btn-group-alert-offline">
                <span class="glyphicon glyphicon-exclamation-sign"></span>
                <span>Cette écran est actuellement hors ligne</span>
                <button type="button" class="btn btn-default">Aide</button>
            </div>
            <a class="alert-link" href="#">Supprimer cet écran</a>
        </div>
        
    </div>
    
    <div class="container">
        
        <div class="btn-group btn-group-justified btn-group" role="group" aria-label="...">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default">Diaporama</button>
            </div>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default">Zone de notiﬁcations</button>
            </div>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default">Chaînes</button>
            </div>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default">Réglages</button>
            </div>
        </div>
        
        <div class="container-channels">
            
            <div class="channel">
                <div class="channel-thumbnail"></div>
                <p>
                    Météo
                </p>
            </div>
            
            <div class="channel">
                <div class="channel-thumbnail"></div>
                <p>
                    Livre numérique
                </p>
            </div>
            
            <div class="channel">
                <button class="btn btn-default glyphicon glyphicon-plus btn-channel" type="button" name="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                </button>

                <p>
                    Ajouter un chaîne
                </p>
            </div>
            
        </div>
        
        <form class="container-setting">
            
            <fieldset>
                <div class="input-group input-group-setting">
                    <label for="">Nom</label>
                    <input class="form-control" type="text" name="name" value="">
                </div>    
        
                <div class="input-group input-group-setting">
                    <label for="">Localisation</label>
                    <input class="form-control" type="text" name="localisation" value="">
                </div>
                
                <div class="google-map"></div>    
                    
                <button class="btn btn-default" type="submit">Enregistrer les réglages</button>
            </fieldset>
            
            <fieldset>
                
                <legend><span class="glyphicon glyphicon-exclamation-sign"></span>Zone périlleuse</legend>
                
                <div class="btn-group btn-group-setting">
                    <button class="btn btn-default btn-default-setting" type="submit">Dissocier de l'écrans</button>
                    <p>
                        La configuration sera préservée dans votre tableau de bord Manivelle, mais l’écran sera réinitialisé à son état original.
                    </p>
                </div>
                
                <div class="btn-group btn-group-setting">
                    <button class="btn btn-default btn-default-setting" type="submit">Supprimer</button>
                    <p>
                        La configuration sera perdue et l’écran sera réinitialisé à son état original.
                    </p>
                </div>
                
            </fieldset>
    
        </form>
        
        <div class="container-table table-responsive">
            
            <!-- .table-notification or .table-slider (diaporama) -->
            
            <table class="table table-striped table-notification">
                <thead>
                    <tr>
                        <th></th>
                        <th>Contenu</th>
                        <th>Conditions d’afﬁchage</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="table-vertical"><button class="btn btn-default glyphicon glyphicon-menu-hamburger" type="button" name="button"></button></td>
                        <td>
                            <div class="table-thumbnail"></div>
                            <div class="table-description">
                                <h3>Événement choisi automatiquement</h3>
                                <span class="table-type">Événements</span>
                                <a class="table-link" href="#">Modiﬁer</a>
                            </div>
                        </td>
                        <td>
                            <span>Afﬁcher en tout temps</span>
                            <a class="table-link" href="#">Modiﬁer</a>
                        </td>
                        <td class="table-vertical"><button class="btn btn-default glyphicon glyphicon-remove" type="button" name="button"></button></td>
                    </tr>
                </tbody>
            </table> 
            <button class="btn btn-default btn-default-table glyphicon glyphicon-plus" type="button" name="button">
                <span>Ajouter une diapositive</span>
            </button>
                   
        </div>
        
    </div>
    
    <!-- .timeline-notification or .timeline-slider (diaporama) -->
    
    <div class="container-timeline timeline-notification">
        
        <div class="container">
            
            <div class="input-group input-group-timeline">
                <label class="timeline-label" for="">Aperçu du diaporama</label>
                <select class="form-control timeline-selector" name="role">
                    <option value="maintenant">maintenant</option>
                    <option value="maintenant">maintenant</option>
                    <option value="maintenant">maintenant</option>
                    <option value="maintenant">maintenant</option>
                </select>
            </div>
            
            <ul class="list-group list-group-timeline">
                <li class="list-group-item">
                    <div class="timeline-thumbnail"></div>
                    Maintenant
                </li>
                <li class="list-group-item">
                    <div class="timeline-thumbnail"></div>
                    16:22:30
                </li>
                <li class="list-group-item">
                    <div class="timeline-thumbnail"></div>
                    <span>16:23:00</span>
                </li>
                <li class="list-group-item list-group-item-separator">
                    <div class="border-timeline">
                        <div class="list-group-item-separator-border"></div>
                        <div class="glyphicon glyphicon-retweet list-group-item-separator-icon"></div>
                        <div class="list-group-item-separator-border"></div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="timeline-thumbnail"></div>
                    <span>16:23:00</span>
                </li>
                <li class="list-group-item">
                    <div class="timeline-thumbnail"></div>
                    <span>16:23:00</span>
                </li>
                <li class="list-group-item">
                    <div class="timeline-thumbnail"></div>
                    <span>16:23:00</span>
                </li>
                <li class="list-group-item list-group-item-separator">
                    <div class="border-timeline">
                        <div class="list-group-item-separator-border"></div>
                        <div class="glyphicon glyphicon-retweet list-group-item-separator-icon"></div>
                        <div class="list-group-item-separator-border"></div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="timeline-thumbnail"></div>
                    <span>16:23:00</span>
                </li>
                <li class="list-group-item">
                    <div class="timeline-thumbnail"></div>
                    <span>16:23:00</span>
                </li>
                <li class="list-group-item">
                    <div class="timeline-thumbnail"></div>
                    <span>16:23:00</span>
                </li>
                <li class="list-group-item list-group-item-separator">
                    <div class="border-timeline">
                        <div class="list-group-item-separator-border"></div>
                        <div class="glyphicon glyphicon-retweet list-group-item-separator-icon"></div>
                        <div class="list-group-item-separator-border"></div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="timeline-thumbnail"></div>
                    <span>16:23:00</span>
                </li>
                <li class="list-group-item">
                    <div class="timeline-thumbnail"></div>
                    <span>16:23:00</span>
                </li>
                <li class="list-group-item">
                    <div class="timeline-thumbnail"></div>
                    <span>16:23:00</span>
                </li>
                
            </ul>
            
        </div>
        
        
        
    </div>
    

    
@endsection
