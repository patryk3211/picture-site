{
  outputs = { nixpkgs, ... }: let
    system = "x86_64-linux";
    pkgs = (import nixpkgs { inherit system; });
  in {
    devShells.${system} = {
      default = pkgs.mkShell {
        buildInputs = with pkgs; [
          php
          mariadb
        ];

        shellHook = ''
          alias mysql='mysql --defaults-file=".dev/.my.cnf"'
        '';
      };

      serve = pkgs.mkShell {
        buildInputs = with pkgs; [
          php
          mariadb
          tmux
        ];

        shellHook = ''
          tmux new 'mysqld --defaults-file=".dev/.my.cnf"' \; split-window -h 'php -S 127.0.0.1:8080 -t public' \;
          exit
        '';
      };
    };
  };
}
