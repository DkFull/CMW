<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('modele/forum/date.php');
if (isset($_GET['id'])) :
    $id = $_GET['id'];
    if (isset($_Joueur_))
        $_JoueurForum_->topic_lu($id, $bddConnection);
    $topicd = $_Forum_->getTopic($id);
    if (!empty($topicd['id'])) :
        if ((Permission::getInstance()->verifPerm("createur") or (Permission::getInstance()->verifPerm('PermsDefault', 'forum', 'perms') >= $topicd['perms'] and Permission::getInstance()->verifPerm('PermsDefault', 'forum', 'perms') >= $topicd['permsCat']) and !$_SESSION['mode']) or ($topicd['perms'] == 0 and $topicd['permsCat'] == 0)) : ?>

            <section id="Post">
                <div class="container-fluid col-md-9 col-lg-9 col-sm-10 mt-4">

                    <div class="row">

                        <nav aria-label="breadcrumb" role="navigation" class="w-100">
                            <ol class="breadcrumb bg-lightest">

                                <li class="breadcrumb-item">
                                    <a href="/">
                                        Accueil
                                    </a>
                                </li>

                                <li class="breadcrumb-item">
                                    <a href="?page=forum">
                                        Forum
                                    </a>
                                </li>

                                <li class="breadcrumb-item">
                                    <a href="?&page=forum_categorie&id=<?= $topicd['id_categorie']; ?>">
                                        <?= $topicd['nom_categorie']; ?>
                                    </a>
                                </li>

                                <?php if (isset($topicd['sous_forum'])) : ?>
                                    <li class="breadcrumb-item">
                                        <a href="?page=forum_categorie&id=<?= $topicd['id_categorie']; ?>&id_sous_forum=<?= $topicd['sous_forum']; ?>">
                                            <?= $topicd['nom_sf']; ?>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <li class="breadcrumb-item" aria-current="page">
                                    <?= $topicd['nom']; ?>
                                </li>

                            </ol>
                        </nav>

                        <div class="col my-3 pr-auto">
                            <div class="card">

                                <div class="card-header text-center">
                                    <h5 class='m-0'>Actions</h5>
                                </div>

                                <div class="card-body categories">
                                    <ul class="nav nav-pills nav-fill">
                                        <?php if (Permission::getInstance()->verifPerm("connect") && $_JoueurForum_->is_followed($id)) : ?>

                                            <li class="categorie-item nav-item">
                                                <a class="nav-link categorie-link" href="?&action=unfollow&id_topic=<?= $topicd['id']; ?>">
                                                    Ne plus suivre cette discussion
                                                </a>
                                            </li>

                                        <?php elseif (Permission::getInstance()->verifPerm("connect")) : ?>

                                            <li class="categorie-item nav-item">
                                                <a class="nav-link categorie-link" href="?&action=follow&id_topic=<?= $topicd['id']; ?>">
                                                    Ne plus suivre cette discussion
                                                </a>
                                            </li>

                                        <?php endif; ?>

                                        <li class="categorie-item nav-item">
                                            <a class="nav-link categorie-link" href="?&page=forum_categorie&id=<?= $topicd['id_categorie']; ?><?= (isset($topicd['sous_forum'])) ? '&id_sous_forum=' . $topicd["sous_forum"] : ""; ?>">
                                                Revenir à l'acceuil de la catégorie
                                            </a>
                                        </li>

                                        <li class="categorie-item nav-item">
                                            <a class="nav-link categorie-link" href="?&page=forum">
                                                Revenir à l'index du forum
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>

                        <?php if (Permission::getInstance()->verifPerm('PermsForum', 'moderation', 'closeTopic') or Permission::getInstance()->verifPerm('PermsForum', 'moderation', 'selTopic') and !$_SESSION['mode']) : ?>

                            <div class="col-md-6 col-lg-4 col-sm-12 my-3 ml-auto">

                                <div class="card">

                                    <div class="card-header text-center">
                                        <h5 class='m-0'>Modération</h5>
                                    </div>

                                    <div class="card-body categories">
                                        <ul class="categorie-content nav nav-tabs">

                                            <li class="categorie-item nav-item">
                                                <div class="dropdown">
                                                    <a class="nav-link categorie-link dropdown-toggle text-center" type="button" id="Actions-Modération" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                        Actions de Modération ....
                                                    </a>
                                                    <div class="dropdown-menu" aria-labeledby="Actions-Modérations">

                                                        <?php if (Permission::getInstance()->verifPerm('PermsForum', 'moderation', 'closeTopic')) :

                                                            if ($topicd['etat'] == 1) : ?>

                                                                <a class="dropdown-item" href="?&action=forum_moderation&id_topic=<?= $id; ?>&choix=4">
                                                                    Ouvrir la discussion
                                                                </a>

                                                            <?php else : ?>

                                                                <a class="dropdown-item" href="?&action=forum_moderation&id_topic=<?= $id; ?>&choix=1">
                                                                    Fermer la discussion
                                                                </a>

                                                            <?php endif; ?>

                                                        <?php endif; ?>

                                                        <?php if (Permission::getInstance()->verifPerm('PermsForum', 'moderation', 'deleteTopic')) : ?>

                                                            <a class="dropdown-item" href="?&action=forum_moderation&id_topic=<?= $id; ?>&choix=2">Supprimer le topic</a>

                                                        <?php endif; ?>

                                                        <?php if (Permission::getInstance()->verifPerm('PermsForum', 'moderation', 'mooveTopic')) : ?>

                                                            <a class="dropdown-item" href="?&action=forum_moderation&id_topic=<?= $id; ?>&choix=3">
                                                                Déplacer la discussion
                                                            </a>

                                                        <?php endif; ?>

                                                    </div>
                                                </div>
                                            </li>

                                            <li class="categorie-item nav-item">
                                                <div class="dropdown">
                                                    <a class="nav-link categorie-link dropdown-toggle text-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Niveau d'accès
                                                    </a>


                                                    <div class="dropdown-menu">

                                                        <form class="px-4 py-3" action="?action=modifPermsTopics" method="POST">
                                                            <div class="form-group">
                                                                <label for="perms">Niveau de permission</label>
                                                                <input type="hidden" name="id" value="<?= $id; ?>">
                                                                <input type="number" min="0" max="100" class="form-control custom-text-input" name="perms" value="<?= $topicd['perms']; ?>">
                                                            </div>
                                                            <button type="submit" class="btn btn-main bg-lightest w-100">Modifier</button>

                                                        </form>
                                                    </div>
                                                </div>
                                            </li>

                                        </ul>
                                    </div>

                                </div>

                            </div>
                        <?php endif; ?>

                    </div>

                    <h3>Sujet : <?= $topicd['nom']; ?></h3>

                    <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12 m-3 border-1">

                            <div class="col-12 text-center">
                                <img class="mx-auto p-3 bg-lightest" src="<?= $_ImgProfil_->getUrlHeadByPseudo($topicd['pseudo']); ?>" style="width: 192px; height: 192px;" alt="avatar de <?= $topicd['pseudo']; ?>" title="<?= $topicd['pseudo']; ?>" />
                            </div>
                            <div class="col-12 mx-auto bg-darkest" style="width: 192px; height: 192px;">
                                <div class="text-center py-3">
                                    <h4 class="text-active">
                                        <?= $topicd['pseudo']; ?>
                                    </h4>
                                    <h6>
                                        <?= $_Forum_->gradeJoueur($topicd['pseudo']); ?>
                                    </h6>

                                    <!-- Edition -->
                                    <?php if (isset($_Joueur_) && ($_Joueur_['pseudo'] == $topicd['pseudo'] || Permission::getInstance()->verifPerm('PermsForum', 'general', 'editTopic') && !$_SESSION['mode'])) : ?>
                                        <form action="?action=editForum" method="post">
                                            <input type="hidden" name="objet" value="topic" />
                                            <input type="hidden" name="id" value="<?= $id; ?>" />
                                            <button type="submit" class="btn btn-secondary w-100 mb-2">
                                                Editer
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if (isset($_Joueur_) && ($_Joueur_['pseudo'] == $topicd['pseudo'] || Permission::getInstance()->verifPerm('PermsForum', 'general', 'deleteTopic') && !$_SESSION['mode'])) : ?>
                                        <form action="?action=remove_topic" method="post">
                                            <input type="hidden" name="id_topic" value="<?= $id; ?>" />
                                            <a class="btn btn-danger w-100 no-hover" role="button" data-toggle="modal" href="#topic_<?= $id; ?>" aria-expanded="false" aria-controls="modalConfirmation">
                                                Supprimer
                                            </a>

                                            <div class="modal fade" id="topic_<?= $id; ?>" tabindex="-1" role="dialog" aria-labelledby="modalConfirmation" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content bg-danger">

                                                        <div class="modal-header bg-danger">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body bg-danger rounded py-4">
                                                            <h5>Voulez-vous vraiement <span class="font-weight-bolder">Supprimer</span> ce topic ?</h5>
                                                            <h6>Plus aucune donnée de ce topic ne pourra être récupéré.</h6>
                                                        </div>

                                                        <div class="modal-footer bg-danger">
                                                            <button type="submit" class="btn btn-secondary w-100">
                                                                Confirmer la suppression de ce Topic !
                                                            </button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    <?php endif; ?>

                                </div>
                            </div>

                        </div>

                        <!-- Contenue du topic de l'auteur -->
                        <div class="col-lg-8 col-md-12 col-sm-12 mt-3 p-4 bg-lightest rounded ml-auto">

                            <?php
                            unset($contenue);
                            $contenue = espacement($topicd['contenue']);
                            $contenue = BBCode($contenue, $bddConnection);

                            $signature = $_Forum_->getSignature($topicd['pseudo']);
                            $signature = espacement($signature);
                            $signature = BBCode($signature, $bddConnection);

                            $mois = switch_date($topicd['mois']);

                            $d_edition = explode('-', $topicd['d_edition']);

                            $countlike = $_Forum_->compteLike($topicd['id'], $count1, 1);
                            $countdislike = $_Forum_->compteDisLike($topicd['id'], $count2, 1);

                            ?>

                            <p class="text-right h6 mt-3">
                                Posté le <?= $topicd['jour']; ?> <?= $mois; ?> <?= $topicd['annee']; ?>
                                <?= ($topicd['d_edition'] != NULL) ? "édité le $d_edition[2]/$d_edition[1]/$d_edition[0]" : "" ?>
                            </p>

                            <hr class="bg-darkest mt-0" style="border-top-style: dotted;">

                            <div class="m-4 h5" style="text-overflow: clip; word-wrap: break-word;">

                                <?= $contenue; ?>
                            </div>

                            <hr class="bg-main mt-3 w-80">

                            <div class="col-12">
                                <div class="ml-3">
                                    <?= $signature; ?>
                                </div>

                                <?php if (isset($_Joueur_)) : ?>
                                    <div class="">
                                        <form action="?&action=signalement_topic" method="post">
                                            <input type="hidden" name="id_topic2" value='<?= $id; ?>' />
                                            <button type="submit" class="btn btn-danger float-right mb-5">Signaler !</button>
                                        </form>
                                    </div>
                                <?php endif; ?>

                            </div>
                            <div class="clearfix"></div>

                            <!-- Système de like/dislike (information) -->

                            <?php if ($count1 > 0 or $count2 > 0) : ?>

                                <div class="row mt-5 mb-2">
                                    <div class="col-md-6 ml-auto text-right">

                                        <?php if ($count1 > 0) : ?>
                                            <p>
                                                <span class="font-weight-bold"><?= $count1 ?></span> personne<?= ($count1 > 1 ? "s" : "") ?> aime<?= ($count1 > 1 ? "s" : "") ?> ça.
                                            </p>
                                        <?php endif; ?>

                                        <?php if ($count2 > 0) : ?>
                                            <p>
                                                <?= $count2 ?> personne<?= ($count2 > 1 ? "s" : "") ?> n'aime<?= ($count2 > 1 ? "nt" : "") ?> pas ça.
                                            </p>
                                        <?php endif; ?>

                                    </div>
                                </div>

                            <?php endif; ?>

                            <!-- Système de like/dislike (action) -->
                            <?php if (Permission::getInstance()->verifPerm("connect")) :

                                if (array_search($_Joueur_['pseudo'], array_column($countlike, 'pseudo')) === FALSE and array_search($_Joueur_['pseudo'], array_column($countdislike, 'pseudo')) === FALSE and $_Joueur_['pseudo'] != $topicd['pseudo']) : ?>

                                    <div class="row justify-content-end">

                                        <div class="col-1">

                                            <form class="form-inline" action="?&action=like" method="post">
                                                <input type="hidden" name="choix" value="1" />
                                                <input type="hidden" name="type" value="1" />
                                                <input type="hidden" name="id_answer" value="<?= $topicd['id']; ?>" />
                                                <button type="submit" class="btn btn-main" title="J'aime"><i class="far fa-thumbs-up"></i></button>
                                            </form>

                                        </div>

                                        <div class="col-2">

                                            <form class="form-inline" action="?&action=like" method="post">
                                                <input type="hidden" name="choix" value="2" />
                                                <input type="hidden" name="type" value="1" />
                                                <input type="hidden" name="id_answer" value="<?= $topicd['id']; ?>" />
                                                <button type="submit" class="btn btn-main" title="Je n'aime pas"><i class="far fa-thumbs-down"></i></button>
                                            </form>

                                        </div>

                                    </div>

                                <?php
                                elseif (array_search($_Joueur_['pseudo'], array_column($countlike, 'pseudo')) !== FALSE or array_search($_Joueur_['pseudo'], array_column($countdislike, 'pseudo')) !== FALSE) :
                                ?>
                                    <div class="row justify-content-end">
                                        <div class="col">

                                            <form class='form' action="?&action=unlike" method="post">
                                                <input type="hidden" name="id_answer" value="<?= $topicd['id']; ?>" />
                                                <input type="hidden" name="type" value="1" />
                                                <button type="submit" class="btn btn-main float-right" title="Ne plus aimer">
                                                    Retirer mon <span class="font-weight-bold">Appréciation</span>
                                                </button>
                                            </form>

                                        </div>
                                    </div>

                                <?php endif; ?>
                            <?php endif; ?>

                        </div>
                    </div>

                    <!-- Affichage des réponses -->
                    <?php
                    $count_Max = $_Forum_->compteReponse($id);
                    $count_nbrOfPages = ceil($count_Max / 20);

                    $page = (isset($_GET['page_post'])) ? $_GET['page_post'] : 1;

                    $count_FirstDisplay = ($page - 1) * 20;
                    $answerd = $_Forum_->affichageReponse($id, $count_FirstDisplay);

                    for ($i = 0; $i < count($answerd); $i++) : ?>







                        <div class="row">

                            <div class="col-lg-3 col-md-12 col-sm-12 m-3 border-1">

                                <div class="col-12 text-center">
                                    <img class="mx-auto p-3 bg-lightest" src="<?= $_ImgProfil_->getUrlHeadByPseudo($answerd[$i]['pseudo']); ?>" style="width: 192px; height: 192px;" alt="avatar de <?= $answerd[$i]['pseudo']; ?>" title="<?= $answerd[$i]['pseudo']; ?>" />
                                </div>
                                <div class="col-12 mx-auto bg-darkest" style="width: 192px; height: 192px;">
                                    <div class="text-center py-3">
                                        <h4 class="text-active">
                                            <?= $answerd[$i]['pseudo']; ?>
                                        </h4>
                                        <h6>
                                            <?= $_Forum_->gradeJoueur($answerd[$i]['pseudo']); ?>
                                        </h6>

                                        <!-- Edition -->
                                        <?php if ($_Joueur_['pseudo'] === $answerd[$i]['pseudo'] or Permission::getInstance()->verifPerm('PermsForum', 'moderation', 'editMessage') and !$_SESSION['mode']) : ?>
                                            <form action="?action=editForum" method="post">
                                                <input type="hidden" name="objet" value="answer" />
                                                <input type="hidden" name="id" value="<?= $answerd[$i]['id']; ?>" />
                                                <button type="submit" class="btn btn-secondary w-100 mb-2">
                                                    Editer
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if ($_Joueur_['pseudo'] === $answerd[$i]['pseudo'] or Permission::getInstance()->verifPerm('PermsForum', 'moderation', 'deleteMessage') and !$_SESSION['mode']) : ?>
                                            <form action="?action=remove_answer" method="post">
                                                <input type="hidden" name="id_answer" value="<?= $id; ?>" />
                                                <input type="hidden" name="page" value="<?= (($_GET['page_post'])) ? $_GET['page_post'] : 1; ?>" />
                                                <a class="btn btn-danger w-100 no-hover" role="button" data-toggle="modal" href="#awnser_<?= $answerd[$i]['id']; ?>" aria-expanded="false" aria-controls="modalConfirmation">
                                                    Supprimer
                                                </a>

                                                <div class="modal fade" id="awnser_<?= $answerd[$i]['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalConfirmation" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content bg-danger">

                                                            <div class="modal-header bg-danger">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body bg-danger rounded py-4">
                                                                <h5>Voulez-vous vraiement <span class="font-weight-bolder">Supprimer</span> ce message ?</h5>
                                                                <h6>Plus aucune donnée de ce message ne pourra être récupéré.</h6>
                                                            </div>

                                                            <div class="modal-footer bg-danger">
                                                                <button type="submit" class="btn btn-secondary w-100">
                                                                    Confirmer la suppression de ce message !
                                                                </button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        <?php endif; ?>

                                    </div>
                                </div>

                            </div>

                            <!-- Contenue du topic de l'auteur -->
                            <div class="col-lg-8 col-md-12 col-sm-12 mt-3 p-4 bg-lightest rounded ml-auto">

                                <?php
                                $answere = $answerd[$i]['contenue'];
                                $answere = espacement($answere);
                                $answere = BBCode($answere, $bddConnection);

                                $signature = $_Forum_->getSignature($answerd[$i]['pseudo']);
                                $signature = espacement($signature);
                                $signature = BBCode($signature, $bddConnection);

                                $mois = switch_date($answerd[$i]['mois']);

                                $d_edition = explode('-', $answerd[$i]['d_edition']);

                                $countlike = $_Forum_->compteLike($answerd[$i]['id'], $count3, 1);
                                $countdislike = $_Forum_->compteDisLike($answerd[$i]['id'], $count4, 1);

                                $count1 += 1;
                                $count2 += 1;
                                ?>

                                <p class="text-right h6 mt-3">
                                    Posté le <?= $answerd[$i]['day']; ?> <?= $mois; ?> <?= $answerd[$i]['annee']; ?>
                                    <?= ($answerd[$i]['d_edition'] != NULL) ? "édité le $d_edition[2]/$d_edition[1]/$d_edition[0]" : "" ?>
                                </p>

                                <hr class="bg-darkest mt-0" style="border-top-style: dotted;">

                                <div class="m-4 h5" style="text-overflow: clip; word-wrap: break-word;">
                                    <?= $answere; ?>
                                </div>

                                <hr class="bg-main mt-3 w-80">

                                <div class="col-12">
                                    <div class="ml-3">
                                        <?= $signature; ?>
                                    </div>

                                    <?php if (isset($_Joueur_)) : ?>
                                        <div class="">
                                            <form action="?&action=signalement_topic" method="post">
                                                <input type="hidden" name="id_topic2" value='<?= $answerd[$i]['id']; ?>' />
                                                <button type="submit" class="btn btn-danger float-right mb-5">Signaler !</button>
                                            </form>
                                        </div>
                                    <?php endif; ?>

                                </div>
                                <div class="clearfix"></div>

                                <!-- Système de like/dislike (information) -->

                                <?php if ($count3 > 0 or $count4 > 0) : ?>

                                    <div class="row mt-5 mb-2">
                                        <div class="col-md-6 ml-auto text-right">

                                            <?php if ($count3 > 0) : ?>
                                                <p>
                                                    <span class="font-weight-bold"><?= $count3 ?></span> personne<?= ($count3 > 1 ? "s" : "") ?> aime<?= ($count3 > 1 ? "s" : "") ?> ça.
                                                </p>
                                            <?php endif; ?>

                                            <?php if ($count4 > 0) : ?>
                                                <p>
                                                    <?= $count4 ?> personne<?= ($count4 > 1 ? "s" : "") ?> n'aime<?= ($count4 > 1 ? "nt" : "") ?> pas ça.
                                                </p>
                                            <?php endif; ?>

                                        </div>
                                    </div>

                                <?php endif; ?>

                                <!-- Système de like/dislike (action) -->
                                <?php if (Permission::getInstance()->verifPerm("connect")) :

                                    if (array_search($_Joueur_['pseudo'], array_column($countlike, 'pseudo')) === FALSE and array_search($_Joueur_['pseudo'], array_column($countdislike, 'pseudo')) === FALSE and $_Joueur_['pseudo'] != $answerd[$i]['pseudo']) : ?>

                                        <div class="row justify-content-end">

                                            <div class="col-1">

                                                <form class="form-inline" action="?&action=like" method="post">
                                                    <input type="hidden" name="choix" value="1" />
                                                    <input type="hidden" name="type" value="1" />
                                                    <input type="hidden" name="id_answer" value="<?= $answerd[$i]['id']; ?>" />
                                                    <button type="submit" class="btn btn-main" title="J'aime"><i class="far fa-thumbs-up"></i></button>
                                                </form>

                                            </div>

                                            <div class="col-2">

                                                <form class="form-inline" action="?&action=like" method="post">
                                                    <input type="hidden" name="choix" value="2" />
                                                    <input type="hidden" name="type" value="1" />
                                                    <input type="hidden" name="id_answer" value="<?= $answerd[$i]['id']; ?>" />
                                                    <button type="submit" class="btn btn-main" title="Je n'aime pas"><i class="far fa-thumbs-down"></i></button>
                                                </form>

                                            </div>

                                        </div>

                                    <?php
                                    elseif (array_search($_Joueur_['pseudo'], array_column($countlike, 'pseudo')) !== FALSE or array_search($_Joueur_['pseudo'], array_column($countdislike, 'pseudo')) !== FALSE) : ?>
                                        <div class="row justify-content-end">
                                            <div class="col">

                                                <form class='form' action="?&action=unlike" method="post">
                                                    <input type="hidden" name="id_answer" value="<?= $answerd[$i]['id']; ?>" />
                                                    <input type="hidden" name="type" value="1" />
                                                    <button type="submit" class="btn btn-main float-right" title="Ne plus aimer">
                                                        Retirer mon <span class="font-weight-bold">Appréciation</span>
                                                    </button>
                                                </form>

                                            </div>
                                        </div>

                                    <?php endif; ?>
                                <?php endif; ?>

                            </div>
                        </div>

                    <?php endfor; ?>
                    <hr class="bg-main" />

                    <nav aria-label="Page Navigation Post">
                        <ul class="pagination justify-content-end">
                            <?php for ($i = 1; $i <= $count_nbrOfPages; $i++) : ?>

                                <li class="page-item">
                                    <a class="page-link" href="?&page=post&id=<?= $id; ?>&page_post=<?= $i; ?>">
                                        <?= $i; ?>
                                    </a>
                                </li>

                            <?php endfor; ?>
                        </ul>
                    </nav>

                    <?php if ($topicd['etat'] == 1 and (!Permission::getInstance()->verifPerm('PermsForum', 'general', 'seeForumHide') and $_SESSION['mode'])) : ?>

                        <div class="d-flex col-12 info-page">
                            <i class="fas fa-window-close notification-icon"></i>
                            <div class="info-content">
                                Le topic est fermé ! Aucune réponse n'est possible !
                            </div>
                        </div>

                    <?php elseif (isset($_Joueur_) && ($topicd['etat'] == 0 or (Permission::getInstance()->verifPerm('PermsForum', 'general', 'seeForumHide') and !$_SESSION['mode']))) : ?>

                        <hr class="my-2 bg-lightest w-80" />

                        <div class="col-8 mx-auto">

                            <?php $data = $_Forum_->isLock($topicd['id_categorie']);
                            if ($data['close'] == 0 or Permission::getInstance()->verifPerm('PermsForum', 'general', 'seeForumHide') and !$_SESSION['mode']) : ?>



                                <form action="?&action=post_answer" method="post">
                                    <input type='hidden' name="id_topic" value="<?= $id; ?>" />

                                    <div class="form-row">

                                        <div class="col-md-12 text-center">
                                            <div class="dropdown" style="display: inline">
                                                <a href="#" role="button" id="font" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i style="text-decoration:none;" class="fas fa-smile"></i>
                                                </a>
                                                <div class="dropdown-menu borderrond" aria-labelledby="font">
                                                    <div class="topheaderdante" style="width: 500px">
                                                        <p class="topheadertext">Clique pour ajouter un smiley!</p>
                                                    </div>
                                                    <?php
                                                    $smileys = getDonnees($bddConnection);
                                                    for ($i = 0; $i < count($smileys['symbole']); $i++) {
                                                        echo '<a class="dropdown-item" style="display: inline; padding: 0; white-space: normal;" href="javascript:insertAtCaret(\'contenue\',\' ' . $smileys['symbole'][$i] . ' \')"><img src="' . $smileys['image'][$i] . '" alt="' . $smileys['symbole'][$i] . '" title="' . $smileys['symbole'][$i] . '" /></a>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <a href="javascript:ajout_text('contenue', 'Ecrivez ici ce que vous voulez mettre en gras', 'ce texte sera en gras', 'b')" style="text-decoration: none;" title="gras"><i class="fas fa-bold" aria-hidden="true"></i></a>
                                            <a href="javascript:ajout_text('contenue', 'Ecrivez ici ce que vous voulez mettre en italique', 'ce texte sera en italique', 'i')" style="text-decoration: none;" title="italique"><i class="fas fa-italic"></i></a>
                                            <a href="javascript:ajout_text('contenue', 'Ecrivez ici ce que vous voulez mettre en souligné', 'ce texte sera en souligné', 'u')" style="text-decoration: none;" title="souligné"><i class="fas fa-underline"></i></a>
                                            <a href="javascript:ajout_text('contenue', 'Ecrivez ici ce que vous voulez mettre en barré', 'ce texte sera barré', 's')" style="text-decoration: none;" title="barré"><i class="fas fa-strikethrough"></i></a>
                                            <a href="javascript:ajout_text('contenue', 'Ecrivez ici ce que vous voulez mettre en aligné à gauche', 'ce texte sera aligné à gauche', 'left')" style="text-decoration: none" title="aligné à gauche"><i class="fas fa-align-left"></i></a>
                                            <a href="javascript:ajout_text('contenue', 'Ecrivez ici ce que vous voulez mettre en centré', 'ce texte sera centré', 'center')" style="text-decoration: none" title="centré"><i class="fas fa-align-center"></i></a>
                                            <a href="javascript:ajout_text('contenue', 'Ecrivez ici ce que vous voulez mettre en aligné à droite', 'ce texte sera aligné à droite', 'right')" style="text-decoration: none" title="aligné à droite"><i class="fas fa-align-right"></i></a>
                                            <a href="javascript:ajout_text('contenue', 'Ecrivez ici ce que vous voulez mettre en justifié', 'ce texte sera justifié', 'justify')" style="text-decoration: none" title="justifié"><i class="fas fa-align-justify"></i></a>
                                            <a href="javascript:ajout_text_complement('contenue', 'Ecrivez ici l\'adresse de votre lien', 'https://craftmywebsite.fr/forum', 'url', 'Entrez le titre de votre lien', 'CraftMyWebsite')" style="text-decoration: none" title="lien"><i class="fas fa-link"></i></a>
                                            <a href="javascript:ajout_text_complement('contenue', 'Ecrivez ici l\'adresse de votre image', 'https://craftmywebsite.fr/img/cat6.png', 'img', 'Entrez ici le titre de votre image (laisser vide si vous ne voulez pas compléter', 'Titre')" style="text-decoration: none" title="image"><i class="fas fa-image"></i></a>
                                            <a href="javascript:ajout_text_complement('contenue', 'Ecrivez ici votre texte en couleur', 'Ce texte sera coloré', 'color', 'Entrer le nom de la couleur en anglais ou en hexaécimal avec le  # : http://www.code-couleur.com/', 'red ou #40A497')" style="text-decoration: none" title="couleur"><i class="fas fa-font"></i></a>
                                            <a href="javascript:ajout_text_complement('contenue', 'Ecrivez ici votre message caché', 'contenue du spoiler', 'spoiler', 'Entrer le titre du message caché (si la case est vide le titre sera \'Spoiler\'', 'Spoiler')" style="text-decoration: none" title="spoiler"><i class="fas fa-flag"></i></a>
                                            <div class="dropdown">
                                                <a href="#" role="button" id="font" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-text-height"></i>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="font">
                                                    <a class="dropdown-item" href="javascript:ajout_text('contenue', 'Ecrivez ici ce que vous voulez mettre en taille 2', 'ce texte sera en taille 2', 'font=2')"><span style="font-size: 2em;">2</span></a>
                                                    <a class="dropdown-item" href="javascript:ajout_text('contenue', 'Ecrivez ici ce que vous voulez mettre en taille 5', 'ce texte sera en taille 5', 'font=5')"><span style="font-size: 5em;">5</span></a>
                                                </div>
                                            </div>
                                            <!--<a href="javascript:ajout_text('contenue', 'Ecrivez ici ce que vous voulez mettre en rouge', 'ce texte sera en rouge', 'color=red')" class="redactor_color_link" style="background-color: rgb(255, 0, 0);"></a>-->
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-12 mt-2 mb-4">
                                            <label for="contenue">Contenue de votre réponse ( 10 000 caractères max ! ) : </label>

                                            <textarea class="form-control custom-text-input" name="contenue" id="contenue" maxlength="10000" rows="5" oninput="previewTopic(this);" required></textarea>
                                        </div>

                                        <div class="col-12">
                                            <label>Prévisualisation</label>
                                            <p style="height: auto; width: auto; background-color: var(--lightest-color-bg);" id="previewTopic"></p>
                                        </div>

                                    </div>

                                    <div class="form-row">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-main w-100">Poster votre réponse</button>
                                        </div>
                                    </div>

                                </form>


                            <?php elseif (!Permission::getInstance()->verifPerm("connect")) : ?>
                                <div class="d-flex col-12 info-page">
                                    <i class="fas fa-sign-in-alt notification-icon"></i>
                                    <div class="info-content">
                                        Connectez-vous pour intéragir !
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>

                    <?php endif; ?>

                </div>
            </section>

<?php else :
            header('Location: ?page=erreur&erreur=7');
        endif;
    else :
        header('Location: index.php');
    endif;
else :
    header('Location: ?page=erreur&erreur=17'); //fatale
endif; ?>