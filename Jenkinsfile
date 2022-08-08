pipeline {
    agent any

    stages {
        stage('Build') {
            steps {
                sh 'git-ftp init -u rance@ofc.quickfixtrips.fun -p ra121588'
            }
        }
        stage('Test') {
            steps {
                echo 'Testing..'
            }
        }
        stage('Deploy') {
            steps {
                echo 'Deploying. revised....'
            }
        }
    }
}