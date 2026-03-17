<script setup lang="ts">
/**
 * Real-Time PGN Stream Monitor
 * Developed for high-performance data visualization and game analysis.
 */
import { ref, nextTick } from 'vue';

// Core Data States
const rawDataInput = ref('');
const transactionLogs = ref<string[]>([]);
const isProcessingStream = ref(false);

// DOM & Audio References
const logContainer = ref<HTMLElement | null>(null);
const eventNotificationSound = ref<HTMLAudioElement | null>(null);
const logEntries = ref<HTMLElement[]>([]);

/**
 * Executes audio feedback on critical game events.
 * Browser-compliant: Triggered via direct user interaction.
 */
const triggerEventAudio = () => {
    if (eventNotificationSound.value) {
        eventNotificationSound.value.currentTime = 0;
        eventNotificationSound.value.play().catch(err => {
            console.warn("Audio feedback deferred: interaction required.", err);
        });
    }
};

/**
 * Parses raw PGN strings and simulates a real-time data stream.
 */
const processDataStream = async () => {
    if (!rawDataInput.value || isProcessingStream.value) return;
    
    // Reset session state
    transactionLogs.value = [];
    logEntries.value = [];
    isProcessingStream.value = true;

    // Data Extraction: Sanitizing PGN metadata and isolating move-sets
    const sanitizedInput = rawDataInput.value.replace(/\[.*?\]/g, '');
    const dataStream = sanitizedInput.match(/(\d+\.+\s?\S+)|(\S+\s\d-\d)|(\S+#)/g) || [];

    for (const [index, entry] of dataStream.entries()) {
        transactionLogs.value.push(entry);
        await nextTick();

        // Auto-scroll to maintain focus on the latest data point
        if (logContainer.value) {
            logContainer.value.scrollTop = logContainer.value.scrollHeight;
        }

        // Logic for Critical Event Detection (Checkmate / Game End)
        const isCriticalEvent = entry.includes('#') || entry.includes('1-0') || entry.includes('0-1');
        
        if (isCriticalEvent) {
            const el = logEntries.value[index];
            if (el) {
                // Apply 'Impact' visual state
                el.classList.add('event-impact-active', 'text-red-500', 'font-bold');
            }
            triggerEventAudio();
        }

        // Stream simulation delay (150ms)
        await new Promise(resolve => setTimeout(resolve, 150));
    }
    
    isProcessingStream.value = false;
};
</script>

<template>
    <div class="flex flex-col items-center justify-center min-h-screen p-6 font-sans text-slate-200 bg-slate-950">
        <div class="w-full max-w-2xl p-8 border shadow-2xl bg-slate-900 rounded-2xl border-slate-800">
            <header class="flex items-center justify-between mb-8 border-b border-slate-800 pb-4">
                <h2 class="text-2xl font-bold tracking-tight text-emerald-400 uppercase">Stream Analytics v1.0</h2>
                <span class="px-3 py-1 text-[10px] font-mono border rounded border-emerald-500/30 text-emerald-500/70">
                    ENGINE_STATUS: {{ isProcessingStream ? 'STREAMING' : 'READY' }}
                </span>
            </header>
            
            <div class="mb-6">
                <label class="block mb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">Input Data Source (PGN)</label>
                <textarea 
                    v-model="rawDataInput"
                    placeholder="Paste standardized PGN data here..."
                    class="w-full h-32 p-4 text-sm transition-all border outline-none bg-slate-950 border-slate-700 rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 font-mono shadow-inner text-emerald-100/80"
                ></textarea>
            </div>

            <div 
                ref="logContainer" 
                class="grid grid-cols-2 gap-x-6 gap-y-1 p-6 mb-8 overflow-y-auto border h-80 bg-slate-950 border-slate-800 rounded-xl scroll-smooth"
            >
                <div 
                    v-for="(log, index) in transactionLogs" 
                    :key="index" 
                    :ref="el => { if (el) logEntries[index] = el as HTMLElement }"
                    class="flex items-center gap-4 py-2 border-b border-slate-900/50 transition-all duration-300 ease-out"
                >
                    <span class="font-mono text-[10px] text-slate-700 w-4">{{ index + 1 }}</span>
                    <span class="text-sm tracking-wide">{{ log }}</span>
                </div>
                <div v-if="transactionLogs.length === 0" class="col-span-2 flex items-center justify-center h-full text-slate-700 text-sm italic">
                    Awaiting Data Stream Injection...
                </div>
            </div>

            <button 
                @click="processDataStream" 
                :disabled="isProcessingStream"
                class="w-full py-4 font-bold tracking-[0.2em] uppercase transition-all rounded-xl shadow-lg border border-emerald-500/20"
                :class="isProcessingStream 
                    ? 'bg-slate-800 text-slate-600 cursor-not-allowed border-none' 
                    : 'bg-emerald-600/90 hover:bg-emerald-500 text-white hover:shadow-emerald-500/10'"
            >
                {{ isProcessingStream ? 'Processing Analysis...' : 'Initialize Analysis' }}
            </button>
        </div>

        <audio 
            ref="eventNotificationSound" 
            src="https://www.chess.com/chess-themes/sounds/standard/game-end.mp3"
            preload="auto"
        ></audio>
    </div>
</template>

<style scoped>
@keyframes impact-shake {
  0%, 100% { transform: translateX(0); }
  20% { transform: translateX(-4px) rotate(-0.5deg); }
  60% { transform: translateX(4px) rotate(0.5deg); }
}

.event-impact-active {
  animation: impact-shake 0.25s cubic-bezier(.36,.07,.19,.97) both;
  background: rgba(239, 68, 68, 0.05);
}

/* Custom Scrollbar */
::-webkit-scrollbar { width: 4px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: #10b981; }
</style>