// resources/js/components/pdf-collection/utils/ProgressAnimator.js
export default class ProgressAnimator {
  startProgress(progressBar) {
    let progress = 0;
    
    const interval = setInterval(() => {
      progress += Math.random() * 15;
      if (progress > 90) progress = 90;
      progressBar.style.width = progress + '%';
    }, 300);
    
    // Return stop function
    return () => clearInterval(interval);
  }
}