import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['player', 'title', 'currentTime', 'duration', 'progress', 'playPause'];
  static values = { current: String };

  audio = null;

  play(event) {
    const btn = event.currentTarget;
    const url = btn.dataset.audioUrl;
    const label = btn.dataset.audioLabel;

    // Même morceau → toggle pause/resume
    if (this.currentValue === url) {
      this.audio.paused ? this.audio.play() : this.audio.pause();
      const isPlaying = !this.audio.paused;
      btn.classList.toggle('playing', isPlaying);
      btn.closest('li')?.classList.toggle('playing', isPlaying);
      this.playPauseTarget.classList.toggle('playing', isPlaying);
      return;
    }

    // Stoppe tout ce qui joue
    this.stopAll();

    // Nouveau morceau
    this.currentValue = url;
    this.audio = new Audio(url);
    this.titleTarget.textContent = label;
    this.playerTarget.classList.remove('!hidden');

    // Mise à jour de la barre de progression
    this.audio.addEventListener('timeupdate', () => this.updateProgress());

    // Durée totale dispo après chargement des métadonnées
    this.audio.addEventListener('loadedmetadata', () => {
      this.durationTarget.textContent = this.formatTime(this.audio.duration);
    });

    // Fin de lecture → reset
    this.audio.addEventListener('ended', () => {
      btn.classList.remove('playing');
      btn.closest('li')?.classList.remove('playing');
      this.playPauseTarget.classList.remove('playing');
      this.playerTarget.classList.add('hidden');
    });

    this.audio.play();

    // Marque le bouton et le li comme actifs
    btn.classList.add('playing');
    btn.closest('li')?.classList.add('playing');
    this.playPauseTarget.classList.add('playing');
  }

  // Bouton play/pause du player
  togglePlayPause() {
    if (!this.audio) return;
    this.audio.paused ? this.audio.play() : this.audio.pause();
    const isPlaying = !this.audio.paused;
    this.playPauseTarget.classList.toggle('playing', isPlaying);

    // Sync le bouton de la liste
    const activeBtn = document.querySelector(`[data-audio-url="${this.currentValue}"]`);
    if (activeBtn) {
      activeBtn.classList.toggle('playing', isPlaying);
      activeBtn.closest('li')?.classList.toggle('playing', isPlaying);
    }
  }

  // Seek via la réglette
  seek(event) {
    if (!this.audio) return;
    const percent = event.target.value / 100;
    this.audio.currentTime = percent * this.audio.duration;
  }

  // Stoppe tout et reset les classes
  stopAll() {
    if (this.audio) this.audio.pause();
    document.querySelectorAll('[data-audio-url]').forEach(b => b.classList.remove('playing'));
    document.querySelectorAll('li.playing').forEach(li => li.classList.remove('playing'));
    this.playPauseTarget.classList.remove('playing');
  }

  // Met à jour la barre et le temps écoulé
  updateProgress() {
    const percent = (this.audio.currentTime / this.audio.duration) * 100;
    this.progressTarget.value = percent;
    this.currentTimeTarget.textContent = this.formatTime(this.audio.currentTime);
  }

  // Formate les secondes en mm:ss
  formatTime(seconds) {
    const m = Math.floor(seconds / 60);
    const s = Math.floor(seconds % 60).toString().padStart(2, '0');
    return `${m}:${s}`;
  }
}
