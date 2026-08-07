/**
 * Vanilla-JS Pusher client for the admin panel's live "course registration"
 * notifications. No Laravel Echo / build step - plain Pusher JS (loaded via
 * CDN) subscribing to a private channel authorized through /broadcasting/auth.
 */
(function () {
    'use strict';

    function showToast(title, message) {
        var toast = document.createElement('div');
        toast.setAttribute('dir', 'rtl');
        toast.style.cssText = [
            'position:fixed', 'bottom:20px', 'left:20px', 'z-index:99999',
            'background:#1e1e2d', 'color:#fff', 'padding:14px 18px',
            'border-radius:10px', 'box-shadow:0 4px 18px rgba(0,0,0,.25)',
            'font-family:inherit', 'font-size:14px', 'max-width:320px',
            'border-right:4px solid #50cd89', 'opacity:0',
            'transition:opacity .3s ease, transform .3s ease',
            'transform:translateY(10px)'
        ].join(';');
        toast.innerHTML = '<div style="font-weight:600;margin-bottom:4px;">' + title + '</div>' +
                '<div style="opacity:.85;">' + message + '</div>';
        document.body.appendChild(toast);

        requestAnimationFrame(function () {
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        });

        setTimeout(function () {
            toast.style.opacity = '0';
            setTimeout(function () {
                toast.remove();
            }, 300);
        }, 6000);
    }

    var audioCtx = null;

    function playChime() {
        try {
            var Ctx = window.AudioContext || window.webkitAudioContext;
            if (!Ctx) {
                return;
            }
            audioCtx = audioCtx || new Ctx();
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }

            var now = audioCtx.currentTime;
            // Two-note ascending chime (E6 -> A6), soft attack/decay.
            var notes = [{freq: 1318.51, start: 0}, {freq: 1760.00, start: 0.14}];

            notes.forEach(function (note) {
                var osc = audioCtx.createOscillator();
                var gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.value = note.freq;

                var t0 = now + note.start;
                gain.gain.setValueAtTime(0, t0);
                gain.gain.linearRampToValueAtTime(0.18, t0 + 0.02);
                gain.gain.exponentialRampToValueAtTime(0.0001, t0 + 0.5);

                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start(t0);
                osc.stop(t0 + 0.55);
            });
        } catch (e) {
            // Ignore - sound is a nice-to-have, never block the notification itself.
        }
    }

    function bumpBadge(el) {
        if (!el) {
            return;
        }
        var current = parseInt(el.textContent, 10);
        if (isNaN(current)) {
            current = 0;
        }
        el.textContent = current + 1;
        el.style.display = '';
    }

    window.initAdminNotifications = function (config) {
        if (!window.Pusher || !config || !config.key) {
            return;
        }

        var pusher = new Pusher(config.key, {
            cluster: config.cluster,
            authEndpoint: config.authEndpoint,
            auth: {
                headers: {
                    'X-CSRF-TOKEN': config.csrfToken
                }
            }
        });

        var channel = pusher.subscribe('private-admin-notifications');

        channel.bind('new-course-registration', function (data) {
            bumpBadge(document.getElementById('header_registrations_badge'));
            bumpBadge(document.getElementById('registrations_badge'));
            showToast('تسجيل جديد في الدورة', (data && data.name) ? data.name : 'تم استلام تسجيل جديد');
            playChime();
        });

        // Browsers block audio before any user gesture; unlock the audio
        // context on the first click/keydown so the chime is ready in time
        // for the first real notification.
        var unlock = function () {
            playChime.unlocked = true;
            document.removeEventListener('click', unlock);
            document.removeEventListener('keydown', unlock);
        };
        document.addEventListener('click', unlock);
        document.addEventListener('keydown', unlock);
    };
})();
