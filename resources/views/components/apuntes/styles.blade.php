{{-- resources/views/components/apuntes/styles.blade.php --}}
<style>
/* Estilos para contenido Markdown */
.markdown-content {
  line-height: 1.7;
}

.markdown-content h1, .markdown-content h2, .markdown-content h3 {
  margin-top: 1.5rem;
  margin-bottom: 1rem;
  font-weight: 600;
}

.markdown-content h1 { font-size: 1.8rem; }
.markdown-content h2 { font-size: 1.5rem; }
.markdown-content h3 { font-size: 1.3rem; }

.markdown-content p {
  margin-bottom: 1rem;
}

.markdown-content ul, .markdown-content ol {
  margin-bottom: 1rem;
  padding-left: 2rem;
}

.markdown-content code {
  background-color: #f8f9fa;
  padding: 0.2rem 0.4rem;
  border-radius: 0.25rem;
  font-size: 0.9em;
}

.markdown-content pre {
  background-color: #f8f9fa;
  padding: 1rem;
  border-radius: 0.5rem;
  overflow-x: auto;
}

.markdown-content blockquote {
  border-left: 4px solid #dee2e6;
  padding-left: 1rem;
  margin: 1rem 0;
  color: #6c757d;
}

.markdown-content strong {
  font-weight: 600;
}

/* Estilos para flashcards */
.flashcards-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
  margin-top: 1rem;
}

@media (max-width: 992px) {
  .flashcards-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 576px) {
  .flashcards-grid {
    grid-template-columns: 1fr;
  }
}

.flashcard {
  height: 250px;
  perspective: 1000px;
  cursor: pointer;
}

.flashcard-inner {
  position: relative;
  width: 100%;
  height: 100%;
  transition: transform 0.6s;
  transform-style: preserve-3d;
}

.flashcard.flipped .flashcard-inner {
  transform: rotateY(180deg);
}

.flashcard-front, .flashcard-back {
  position: absolute;
  width: 100%;
  height: 100%;
  backface-visibility: hidden;
  border-radius: 0.5rem;
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.flashcard-front {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.flashcard-back {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  color: white;
  transform: rotateY(180deg);
}

.flashcard-label {
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  opacity: 0.9;
  font-weight: 600;
}

.flashcard-content {
  flex-grow: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  font-size: 1.1rem;
  font-weight: 500;
  padding: 1rem 0;
}

.flashcard-back .flashcard-content {
  font-size: 1rem;
}

.flashcard-hint {
  text-align: center;
  font-size: 0.85rem;
  opacity: 0.8;
}

/* Tabs personalizados */
.nav-pills .nav-link {
  border-radius: 0.5rem;
  padding: 0.75rem 1.5rem;
  font-weight: 500;
}

.nav-pills .nav-link:not(.active) {
  color: #6c757d;
}

.nav-pills .nav-link:not(.active):hover {
  background-color: #f8f9fa;
}

@media (max-width: 768px) {
  .nav-pills {
    flex-direction: column;
  }
  
  .nav-pills .nav-link {
    margin-bottom: 0.5rem;
  }
}
</style>