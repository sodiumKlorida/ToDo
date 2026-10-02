<div id="imageModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/70 p-4">
    <div id="imageModalContent" class="relative w-full max-w-4xl rounded-[28px] border-4 border-slate-900 bg-white p-4 shadow-[12px_12px_0_#111827]">
        <button type="button" id="closeImageModal" class="absolute right-3 top-3 z-10 rounded-full border-4 border-slate-900 bg-rose-300 px-3 py-1 text-sm font-black shadow-[2px_2px_0_#111827]">
            X
        </button>

        <div class="mb-4 flex flex-wrap items-center justify-center gap-3">
            <button type="button" data-tool="pen" class="tool-button rounded-xl border-4 border-slate-900 bg-yellow-300 px-3 py-2 text-sm font-black shadow-[3px_3px_0_#111827]">Pen</button>
            <button type="button" data-tool="eraser" class="tool-button rounded-xl border-4 border-slate-900 bg-slate-200 px-3 py-2 text-sm font-black shadow-[3px_3px_0_#111827]">Penghapus</button>
            <button type="button" id="clearAnnotation" class="rounded-xl border-4 border-slate-900 bg-red-200 px-3 py-2 text-sm font-black shadow-[3px_3px_0_#111827]">Hapus Coretan</button>
            <div class="flex items-center gap-2">
                <button type="button" id="zoomOut" class="h-10 w-10 rounded-xl border-4 border-slate-900 bg-slate-200 text-lg font-black shadow-[3px_3px_0_#111827]" aria-label="Perkecil gambar" title="Perkecil gambar">-</button>
                <span id="zoomLevel" class="min-w-14 text-center text-sm font-black tabular-nums" aria-live="polite">100%</span>
                <button type="button" id="zoomIn" class="h-10 w-10 rounded-xl border-4 border-slate-900 bg-slate-200 text-lg font-black shadow-[3px_3px_0_#111827]" aria-label="Perbesar gambar" title="Perbesar gambar">+</button>
                <button type="button" id="resetZoom" class="rounded-xl border-4 border-slate-900 bg-white px-3 py-2 text-sm font-black shadow-[3px_3px_0_#111827]">Pas</button>
            </div>
            <button type="button" id="toggleImageFullscreen" class="rounded-xl border-4 border-slate-900 bg-lime-300 px-3 py-2 text-sm font-black shadow-[3px_3px_0_#111827]" aria-pressed="false">Layar Penuh</button>
        </div>

        <div id="imageFrame" class="image-modal-image-frame relative max-h-[65vh] overflow-auto rounded-[20px] border-4 border-slate-900 bg-slate-100">
            <div id="imageStage" class="relative mx-auto">
                <img id="modalImage" src="" alt="Issue preview" class="block max-h-[65vh] w-full">
                <canvas id="annotationCanvas" class="absolute left-0 top-0 cursor-crosshair"></canvas>
            </div>
        </div>
        <p id="modalTitle" class="mt-4 text-center text-lg font-black text-slate-900"></p>
    </div>
</div>

<style>
    #imageModalContent.image-modal-fullscreen {
        position: fixed;
        inset: 0;
        z-index: 60;
        display: flex;
        width: 100vw;
        height: 100vh;
        height: 100dvh;
        max-width: none;
        flex-direction: column;
        border: 0;
        border-radius: 0;
        padding: 12px;
        box-shadow: none;
    }

    #imageModalContent.image-modal-fullscreen .image-modal-image-frame {
        min-height: 0;
        flex: 1;
        max-height: none;
        border: 0;
        border-radius: 0;
    }

    #imageModalContent.image-modal-fullscreen #modalImage {
        max-height: 100%;
        object-fit: contain;
    }
</style>

@pushOnce('scripts', 'issue-image-modal')
    <script>
        const modal = document.getElementById('imageModal');
        const modalContent = document.getElementById('imageModalContent');
        const modalImage = document.getElementById('modalImage');
        const modalTitle = document.getElementById('modalTitle');
        const closeButton = document.getElementById('closeImageModal');
        const fullscreenButton = document.getElementById('toggleImageFullscreen');
        const annotationCanvas = document.getElementById('annotationCanvas');
        const clearAnnotationButton = document.getElementById('clearAnnotation');
        const imageFrame = document.getElementById('imageFrame');
        const imageStage = document.getElementById('imageStage');
        const zoomOutButton = document.getElementById('zoomOut');
        const zoomInButton = document.getElementById('zoomIn');
        const resetZoomButton = document.getElementById('resetZoom');
        const zoomLevel = document.getElementById('zoomLevel');
        const toolButtons = document.querySelectorAll('.tool-button');
        const ctx = annotationCanvas.getContext('2d');

        let currentTool = 'pen';
        let isDrawing = false;
        let isCssFullscreen = false;
        let zoom = 1;
        let baseImageWidth = 0;
        let baseImageHeight = 0;
        let lastX = 0;
        let lastY = 0;

        const setFullscreenLayout = (isFullscreen) => {
            modalContent.classList.toggle('image-modal-fullscreen', isFullscreen);
            fullscreenButton.textContent = isFullscreen ? 'Keluar Layar Penuh' : 'Layar Penuh';
            fullscreenButton.setAttribute('aria-pressed', String(isFullscreen));
            requestAnimationFrame(fitImageToFrame);
        };

        const toggleFullscreen = async () => {
            if (document.fullscreenElement === modalContent) {
                await document.exitFullscreen();
                isCssFullscreen = false;
                setFullscreenLayout(false);
                return;
            }

            if (isCssFullscreen) {
                isCssFullscreen = false;
                setFullscreenLayout(false);
                return;
            }

            if (modalContent.requestFullscreen) {
                try {
                    await modalContent.requestFullscreen();
                    setFullscreenLayout(true);
                    return;
                } catch {
                    // Fall back to an in-page fullscreen layout when browser fullscreen is unavailable.
                }
            }

            isCssFullscreen = true;
            setFullscreenLayout(true);
        };

        const resizeCanvasToImage = (preserveDrawing = false) => {
            const width = baseImageWidth * zoom;
            const height = baseImageHeight * zoom;
            const ratio = Math.min(window.devicePixelRatio || 1, 2);
            let previousCanvas;

            if (preserveDrawing && annotationCanvas.width && annotationCanvas.height) {
                previousCanvas = document.createElement('canvas');
                previousCanvas.width = annotationCanvas.width;
                previousCanvas.height = annotationCanvas.height;
                previousCanvas.getContext('2d').drawImage(annotationCanvas, 0, 0);
            }

            imageStage.style.width = width + 'px';
            imageStage.style.height = height + 'px';
            modalImage.style.width = width + 'px';
            modalImage.style.height = height + 'px';
            modalImage.style.maxWidth = 'none';
            modalImage.style.maxHeight = 'none';
            annotationCanvas.style.width = width + 'px';
            annotationCanvas.style.height = height + 'px';
            annotationCanvas.width = Math.round(width * ratio);
            annotationCanvas.height = Math.round(height * ratio);
            ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.lineWidth = currentTool === 'eraser' ? 18 : 4;
            ctx.strokeStyle = '#111827';
            ctx.globalCompositeOperation = currentTool === 'eraser' ? 'destination-out' : 'source-over';

            if (previousCanvas) {
                ctx.drawImage(previousCanvas, 0, 0, previousCanvas.width, previousCanvas.height, 0, 0, width, height);
            }

            zoomLevel.textContent = Math.round(zoom * 100) + '%';
        };

        const fitImageToFrame = () => {
            if (!modalImage.naturalWidth || !modalImage.naturalHeight) {
                return;
            }

            const width = imageFrame.clientWidth;
            const height = imageFrame.clientHeight;
            const scale = Math.min(width / modalImage.naturalWidth, height / modalImage.naturalHeight);
            baseImageWidth = modalImage.naturalWidth * scale;
            baseImageHeight = modalImage.naturalHeight * scale;
            resizeCanvasToImage(true);
        };

        const setZoom = (nextZoom) => {
            zoom = Math.min(4, Math.max(1, nextZoom));
            resizeCanvasToImage(true);
        };

        const setTool = (tool) => {
            currentTool = tool;
            toolButtons.forEach((button) => {
                const isActive = button.dataset.tool === tool;
                button.classList.toggle('bg-yellow-300', isActive && tool === 'pen');
                button.classList.toggle('bg-slate-200', isActive && tool === 'eraser');
                button.classList.toggle('ring-4', isActive);
                button.classList.toggle('ring-slate-900', isActive);
            });

            ctx.lineWidth = currentTool === 'eraser' ? 18 : 4;
            ctx.globalCompositeOperation = currentTool === 'eraser' ? 'destination-out' : 'source-over';
        };

        const clearAnnotation = () => {
            ctx.clearRect(0, 0, annotationCanvas.width, annotationCanvas.height);
        };

        const getCanvasPoint = (event) => {
            const rect = annotationCanvas.getBoundingClientRect();
            return {
                x: event.clientX - rect.left,
                y: event.clientY - rect.top,
            };
        };

        const startDrawing = (event) => {
            const point = getCanvasPoint(event);
            isDrawing = true;
            lastX = point.x;
            lastY = point.y;
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
        };

        const draw = (event) => {
            if (!isDrawing) return;

            const point = getCanvasPoint(event);
            ctx.lineWidth = currentTool === 'eraser' ? 18 : 4;
            ctx.strokeStyle = '#111827';
            ctx.globalCompositeOperation = currentTool === 'eraser' ? 'destination-out' : 'source-over';
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(point.x, point.y);
            ctx.stroke();
            lastX = point.x;
            lastY = point.y;
        };

        const stopDrawing = () => {
            isDrawing = false;
            ctx.closePath();
        };

        const openImageModal = (imageUrl, title) => {
            zoom = 1;
            baseImageWidth = 0;
            baseImageHeight = 0;
            imageFrame.scrollTo(0, 0);
            imageStage.removeAttribute('style');
            modalImage.removeAttribute('style');
            annotationCanvas.removeAttribute('style');
            modalImage.src = imageUrl;
            modalTitle.textContent = title;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('image-modal-open');
            setTimeout(() => {
                fitImageToFrame();
                clearAnnotation();
                setTool('pen');
            }, 50);
        };

        const closeImageModal = () => {
            if (document.fullscreenElement === modalContent) {
                document.exitFullscreen();
            }

            isCssFullscreen = false;
            setFullscreenLayout(false);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('image-modal-open');
            modalImage.src = '';
            clearAnnotation();
        };

        document.querySelectorAll('[data-image-modal-trigger]').forEach((button) => {
            button.addEventListener('click', () => {
                openImageModal(button.dataset.imageModalTrigger, button.dataset.imageTitle);
            });
        });

        toolButtons.forEach((button) => {
            button.addEventListener('click', () => setTool(button.dataset.tool));
        });

        clearAnnotationButton.addEventListener('click', clearAnnotation);
        fullscreenButton.addEventListener('click', toggleFullscreen);
        zoomOutButton.addEventListener('click', () => setZoom(zoom - 0.5));
        zoomInButton.addEventListener('click', () => setZoom(zoom + 0.5));
        resetZoomButton.addEventListener('click', () => setZoom(1));
        annotationCanvas.addEventListener('pointerdown', startDrawing);
        annotationCanvas.addEventListener('pointermove', draw);
        annotationCanvas.addEventListener('pointerup', stopDrawing);
        annotationCanvas.addEventListener('pointerleave', stopDrawing);
        annotationCanvas.addEventListener('pointercancel', stopDrawing);
        closeButton.addEventListener('click', closeImageModal);

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeImageModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeImageModal();
            }
        });

        modalImage.addEventListener('load', fitImageToFrame);
        document.addEventListener('fullscreenchange', () => {
            if (document.fullscreenElement !== modalContent) {
                isCssFullscreen = false;
            }

            setFullscreenLayout(document.fullscreenElement === modalContent || isCssFullscreen);
        });
        window.addEventListener('resize', fitImageToFrame);
        setTool('pen');
    </script>
@endPushOnce
